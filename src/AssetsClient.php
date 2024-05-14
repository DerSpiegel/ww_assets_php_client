<?php

namespace DerSpiegel\WoodWingAssetsClient;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Exception\NotAuthorizedAssetsException;
use DerSpiegel\WoodWingAssetsClient\Service\ApiLoginRequest;
use DerSpiegel\WoodWingAssetsClient\Service\LoginRequest;
use DerSpiegel\WoodWingAssetsClient\Service\LogoutResponse;
use DomainException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use SebastianBergmann\Timer\Timer;


/**
 * Class AssetsClient
 * @package DerSpiegel\WoodWingAssetsClient
 */
class AssetsClient
{
    const AUTH_METHOD_BEARER_TOKEN = 1;
    const AUTH_METHOD_CSRF_TOKEN = 2;
    const AUTH_METHOD_AUTHCRED = 3;

    const MAX_LOGIN_ATTEMPTS_PER_SECOND = 10;

    const RELATION_TARGET_ANY = 'any';
    const RELATION_TARGET_CHILD = 'child';
    const RELATION_TARGET_PARENT = 'parent';

    protected Client $httpClient;

    private bool $allowReLogin = true;
    protected string $authCred = '';
    protected int $authMethod = self::AUTH_METHOD_BEARER_TOKEN;
    protected string $bearerToken = '';
    protected array $cookies = [];
    protected string $csrfToken = '';
    protected string $httpUserAgent = '';
    private array $loginAttempts = [];
    protected int $requestTimeout = 60;


    public function __construct(
        readonly AssetsConfig $config,
        readonly LoggerInterface $logger
    )
    {
        $this->httpClient = $this->newHttpClient();
        $this->setHttpUserAgent($this->getDefaultHttpUserAgent());
    }


    /**
     * @param bool $allowReLogin
     * @return self
     */
    public function setAllowReLogin(bool $allowReLogin): self
    {
        $this->allowReLogin = $allowReLogin;
        return $this;
    }


    /**
     * @param int $authMethod
     */
    public function setAuthMethod(int $authMethod): void
    {
        $this->authMethod = $authMethod;
    }


    /**
     * @return int
     */
    public function getRequestTimeout(): int
    {
        return $this->requestTimeout;
    }


    /**
     * @param int $seconds
     * @return self
     */
    public function setRequestTimeout(int $seconds): self
    {
        $this->requestTimeout = max($seconds, 1);
        return $this;
    }


    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @param bool $multipart - whether to send the data as multipart or application/json
     * @param bool $sendToken
     * @return ResponseInterface
     */
    public function request(
        string $method,
        string $url,
        array  $data = [],
        bool   $multipart = true,
        bool   $sendToken = true
    ): ResponseInterface
    {
        $options = [
            RequestOptions::HEADERS => ['User-Agent' => $this->getHttpUserAgent()],
            RequestOptions::TIMEOUT => $this->getRequestTimeout(),
            RequestOptions::VERIFY => $this->config->verifySslCertificate
        ];

        if ($sendToken) {
            switch ($this->authMethod) {
                case self::AUTH_METHOD_BEARER_TOKEN:
                    $options[RequestOptions::HEADERS]['Authorization'] = $this->getToken();
                    break;
                case self::AUTH_METHOD_CSRF_TOKEN:
                    $options[RequestOptions::HEADERS]['X-CSRF-TOKEN'] = $this->getToken();
                    break;
                case self::AUTH_METHOD_AUTHCRED:
                    $data['authcred'] = $this->getToken();
                    break;
                default:
                    throw new DomainException(sprintf("%s: Invalid Authentication method <%d>", __METHOD__, $this->authMethod));
            }
        }

        if ($multipart) {
            // send data as multipart (e.g. for `services/*`)
            $options[RequestOptions::MULTIPART] = $this->dataToMultipart($data);
        } else {

            // send data according to the request method
            //send data as application/json (e.g. for `api/*`)

            switch ($method) {
                case 'GET':
                case 'HEAD':
                    // send data as query string
                    $url .= '?' . http_build_query($data);
                    break;
                case 'POST':
                case 'PUT':
                default:
                    // send data as application/json
                    $options[RequestOptions::BODY] = json_encode($data);
                    $options[RequestOptions::HEADERS]['Content-Type'] = 'application/json';
                    break;
            }
        }

        // cookies
        $jar = new CookieJar();
        foreach ($this->cookies as $cookie) {
            $jar->setCookie(new SetCookie($cookie));
        }
        $options[RequestOptions::COOKIES] = $jar;

        try {
            $httpClient = $this->httpClient;

            $timer = new Timer();
            $timer->start();

            $response = $httpClient->request($method, $url, $options);

            $duration = $timer->stop();
            $this->logger->debug(sprintf('%s request to %s took %s.', $method, $url, $duration->asString()));

            // store cookies for further requests
            $this->cookies = $jar->toArray();

            return $response;
        } catch (GuzzleException $e) {
            throw AssetsException::createFromCode($e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * @throws JsonException
     */
    public function serviceRequest(string $method, string $service, array $data = [], bool $multipart = true): array
    {
        $httpResponse = $this->rawServiceRequest($method, $service, $data, $multipart);
        return AssetsUtils::parseJsonResponse($httpResponse->getBody());
    }


    public function rawServiceRequest(string $method, string $service, array $data, bool $multipart): ResponseInterface
    {
        $url = sprintf(
            '%sservices/%s',
            $this->config->url,
            $service
        );

        $loginRequest = in_array($service, ['login', 'apilogin']);

        if ($loginRequest) {
            $this->preventLoginLoops();
        }

        try {
            $httpResponse = $this->request($method, $url, $data, $multipart, !$loginRequest);

            // Even usually-binary responses like "checkout and download" return JSON on error (i.e. "not logged in").
            // So when we get JSON back, run it through AssetsUtils::parseJsonResponse() which throws an exception on error.
            if (str_starts_with($httpResponse->getHeaderLine('content-type'), 'application/json')) {
                AssetsUtils::parseJsonResponse($httpResponse->getBody());
            }
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case 401: // Unauthorized
                    // TODO: prevent a possible loop here?

                    // re-login
                    if (!$this->reLogin()) {
                        throw $e;
                    }

                    // try again
                    return $this->rawServiceRequest($method, $service, $data, $multipart);
                default:
                    // something went wrong
                    throw $e;
            }
        }

        return $httpResponse;
    }


    /**
     * @param string $method
     * @param string $urlPath
     * @param array $data
     * @return array
     * @throws JsonException
     */
    public function apiRequest(string $method, string $urlPath, array $data = []): array
    {
        $url = sprintf(
            '%sapi/%s',
            $this->config->url,
            $urlPath
        );

        try {
            $httpResponse = $this->request($method, $url, $data, false);

            $responseBbody = (string)$httpResponse->getBody();

            if (empty($responseBbody)) {
                return [];
            }

            return AssetsUtils::parseJsonResponse($responseBbody);
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case 401: // Unauthorized
                    // TODO: prevent a possible loop here?

                    // re-login
                    if (!$this->reLogin()) {
                        throw $e;
                    }

                    // try again
                    return $this->apiRequest($method, $urlPath, $data);
                default:
                    // something went wrong
                    throw $e;
            }
        }
    }


    /**
     * @param string $method
     * @param string $urlPath
     * @param array $data
     * @return array
     * @throws JsonException
     */
    public function privateApiRequest(string $method, string $urlPath, array $data = []): array
    {
        $url = sprintf(
            '%sprivate-api/%s',
            $this->config->url,
            $urlPath
        );

        try {
            $httpResponse = $this->request($method, $url, $data, false);

            $responseBbody = (string)$httpResponse->getBody();

            if (empty($responseBbody)) {
                return [];
            }

            return AssetsUtils::parseJsonResponse($responseBbody);
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case 401: // Unauthorized
                    // TODO: prevent a possible loop here?

                    // re-login
                    if (!$this->reLogin()) {
                        throw $e;
                    }

                    // try again
                    return $this->privateApiRequest($method, $urlPath, $data);
                default:
                    // something went wrong
                    throw $e;
            }
        }
    }


    /**
     * @return Client
     */
    protected function newHttpClient(): Client
    {
        $stack = HandlerStack::create();

        $stack->push(
            Middleware::log(
                $this->logger,
                new MessageFormatter('AssetsClient {method} request to {uri}. Assets response headers: {res_headers}'),
                LogLevel::DEBUG
            )
        );

        return new Client(['handler' => $stack]);
    }


    /**
     * @return string
     */
    protected function getDefaultHttpUserAgent(): string
    {
        return sprintf(
            'der-spiegel/ww-assets-client (https://github.com/DerSpiegel/ww_assets_php_client) PHP/%s',
            PHP_VERSION
        );
    }


    /**
     * @return string
     */
    public function getHttpUserAgent(): string
    {
        return $this->httpUserAgent;
    }


    /**
     * @param string $httpUserAgent
     * @return self
     */
    public function setHttpUserAgent(string $httpUserAgent): self
    {
        $this->httpUserAgent = $httpUserAgent;
        return $this;
    }


    /**
     * @return bool
     */
    private function reLogin(): bool
    {
        try {
            $this->getToken(true);
            return true;
        } catch (Exception) {
            return false;
        }
    }


    /**
     * @param bool $force
     * @return string
     */
    public function getToken(bool $force = false): string
    {
        return match ($this->authMethod) {
            self::AUTH_METHOD_BEARER_TOKEN => $this->getBearerToken($force),
            self::AUTH_METHOD_CSRF_TOKEN => $this->getCsrfToken($force),
            self::AUTH_METHOD_AUTHCRED => $this->getAuthCred(),
            default => throw new DomainException(sprintf("%s: Invalid Authentication method <%d>", __METHOD__,
                $this->authMethod)),
        };
    }


    private function preventLoginLoops(): void
    {
        $key = time();
        $this->loginAttempts[$key] = ($this->loginAttempts[$key] ?? 0) + 1;
        if ($this->loginAttempts[$key] > self::MAX_LOGIN_ATTEMPTS_PER_SECOND) {
            throw new AssetsException(sprintf("%s: MAX_LOGIN_ATTEMPTS_PER_SECOND exceeded", __METHOD__));
        }
    }


    /**
     * @param array $data
     * @return array
     */
    private function dataToMultipart(array $data): array
    {
        $multipart = [];
        foreach ($data as $name => $value) {
            $multipart[] = [
                'name' => $name,
                'contents' => $value
            ];
        }
        return $multipart;
    }


    /**
     * @param string $bearerToken
     */
    public function setBearerToken(string $bearerToken): void
    {
        $this->bearerToken = $bearerToken;
        $this->setAuthMethod(self::AUTH_METHOD_BEARER_TOKEN);
    }


    /**
     * Perform API login and return Authorization token
     * @param bool $force
     * @return string
     */
    public function getBearerToken(bool $force = false): string
    {
        if ((strlen($this->bearerToken) > 0) && (!$force)) {
            return $this->bearerToken;
        }

        if (!$this->allowReLogin) {
            throw new NotAuthorizedAssetsException(sprintf("%s: Not Authorized", __METHOD__), NotAuthorizedAssetsException::CODE);
        }

        $response = ApiLoginRequest::createFromConfig($this)();

        if (!$response->loginSuccess) {
            throw new AssetsException(sprintf('%s: Assets API login failed: %s', __METHOD__,
                $response->loginFaultMessage));
        }

        if (strlen($response->authToken) === 0) {
            throw new AssetsException(sprintf('%s: Assets API login succeeded, but authToken is empty', __METHOD__));
        }

        $this->bearerToken = 'Bearer ' . $response->authToken;

        return $this->bearerToken;
    }


    /**
     * @param string $csrfToken
     * @param array $cookies
     */
    public function setCsrfToken(string $csrfToken, array $cookies = []): void
    {
        $this->csrfToken = $csrfToken;
        $this->cookies = $cookies;
        $this->setAuthMethod(self::AUTH_METHOD_CSRF_TOKEN);
    }


    /**
     * @return string
     */
    public function getAuthCred(): string
    {
        return $this->authCred;
    }


    /**
     * @param string $authCred
     */
    public function setAuthCred(string $authCred): void
    {
        $this->authCred = $authCred;
        $this->setAuthMethod(self::AUTH_METHOD_AUTHCRED);
    }


    /**
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }


    /**
     * @param bool $force
     * @return string
     */
    public function getCsrfToken(bool $force = false): string
    {
        if ((strlen($this->csrfToken) > 0) && (!$force)) {
            return $this->csrfToken;
        }

        if (!$this->allowReLogin) {
            throw new NotAuthorizedAssetsException(sprintf("%s: Not Authorized", __METHOD__), NotAuthorizedAssetsException::CODE);
        }

        $response = LoginRequest::createFromConfig($this)();

        if (!$response->loginSuccess) {
            throw new AssetsException(sprintf('%s: Assets login failed: %s', __METHOD__,
                $response->loginFaultMessage));
        }

        if (strlen($response->csrfToken) === 0) {
            throw new AssetsException(sprintf('%s: Assets login succeeded, but csrfToken is empty', __METHOD__));
        }

        $this->csrfToken = $response->csrfToken;

        return $this->csrfToken;
    }


    /**
     * @param bool $cleanUpToken
     * @return LogoutResponse
     */
    public function logout(bool $cleanUpToken = true): LogoutResponse
    {
        try {
            $httpResponse = $this->serviceRequest('POST', 'logout');
            $logout = LogoutResponse::createFromJson($httpResponse);

            if ($cleanUpToken) {
                $this->bearerToken = '';
            }

            return $logout;

        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Logout POST request failed', __METHOD__), $e->getCode(), $e);
        }
    }


    /**
     * @param string $url
     * @param string $targetPath
     */
    public function downloadFileToPath(string $url, string $targetPath): void
    {
        try {
            $httpResponse = $this->request('GET', $url, ['forceDownload' => 'true'], false);
            $this->writeResponseBodyToPath($httpResponse, $targetPath);
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Failed to download <%s>: %s', __METHOD__, $url, $e->getMessage()),
                $e->getCode(), $e);
        }
    }


    /**
     * @param ResponseInterface $httpResponse
     * @param string $targetPath
     */
    public function writeResponseBodyToPath(ResponseInterface $httpResponse, string $targetPath): void
    {
        $fp = fopen($targetPath, 'wb');

        if ($fp === false) {
            throw new AssetsException(sprintf('%s: Failed to open <%s> for writing', __METHOD__,
                $targetPath));
        }

        $ok = true;

        while ($data = $httpResponse->getBody()->read(1024)) {
            $ok = fwrite($fp, $data);

            if ($ok === false) {
                break;
            }
        }

        fclose($fp);

        if (!$ok) {
            throw new AssetsException(sprintf('%s: Failed to write HTTP response to <%s>', __METHOD__,
                $targetPath));
        }
    }


    /**
     * @param string $assetId
     * @return string
     */
    public function buildOriginalFileUrl(string $assetId): string
    {
        return "{$this->config->url}file/$assetId/*/$assetId";
    }
}
