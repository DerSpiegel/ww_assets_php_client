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
    const int AUTH_METHOD_BEARER_TOKEN = 1;
    const int AUTH_METHOD_CSRF_TOKEN = 2;
    const int AUTH_METHOD_AUTHCRED = 3;

    const int MAX_LOGIN_ATTEMPTS_PER_SECOND = 10;

    const string RELATION_TARGET_ANY = 'any';
    const string RELATION_TARGET_CHILD = 'child';
    const string RELATION_TARGET_PARENT = 'parent';

    protected Client $httpClient;
    readonly AssetsHealth $health;

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
    ) {
        $this->httpClient = $this->newHttpClient();
        $this->setHttpUserAgent($this->config->httpUserAgent ?? $this->getDefaultHttpUserAgent());

        $this->health = $config->health ?? new AssetsHealth();
    }


    public function setAllowReLogin(bool $allowReLogin): self
    {
        $this->allowReLogin = $allowReLogin;
        return $this;
    }


    public function setAuthMethod(int $authMethod): void
    {
        $this->authMethod = $authMethod;
    }


    public function getRequestTimeout(): int
    {
        return $this->requestTimeout;
    }


    public function setRequestTimeout(int $seconds): self
    {
        $this->requestTimeout = max($seconds, 1);
        return $this;
    }


    public function request(
        string $method,
        string $url,
        array $data = [],
        bool $multipart = true, // Whether to send the data as multipart or application/json
        bool $sendToken = true
    ): ResponseInterface {
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
                    throw new DomainException(
                        sprintf("%s: Invalid Authentication method <%d>", __METHOD__, $this->authMethod)
                    );
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
                    $url = AssetsUtils::buildGetUrl($url, $data);
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

        $httpClient = $this->httpClient;

        $timer = new Timer();
        $timer->start();

        try {
            $response = $httpClient->request($method, $url, $options);
        } catch (GuzzleException $e) {
            $this->health->setServiceIsAvailableByException($e);
            throw $e;
        }

        $duration = $timer->stop();
        $this->logger->debug(sprintf('%s request to %s took %s.', $method, $url, $duration->asString()));

        $this->health->setServiceIsAvailable(true);

        // store cookies for further requests
        $this->cookies = $jar->toArray();

        return $response;
    }


    public function serviceRequest(
        string $method,
        string $service,
        array $data = [],
        bool $multipart = true
    ): ResponseInterface {
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
                    return $this->serviceRequest($method, $service, $data, $multipart);
                default:
                    // something went wrong
                    $this->logger->error(
                        sprintf(
                            '%s: %s request to <%s> failed: <%d> "%s"',
                            __METHOD__,
                            $method,
                            $url,
                            $e->getCode(),
                            $e->getMessage()
                        )
                    );
                    throw $e;
            }
        }

        return $httpResponse;
    }


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
                    $this->logger->error(
                        sprintf(
                            '%s: %s request to <%s> failed: <%d> "%s"',
                            __METHOD__,
                            $method,
                            $url,
                            $e->getCode(),
                            $e->getMessage()
                        )
                    );
                    throw $e;
            }
        }
    }


    public function privateApiRequest(string $method, string $urlPath, array $data = []): ResponseInterface
    {
        $url = sprintf(
            '%sprivate-api/%s',
            $this->config->url,
            $urlPath
        );

        try {
            $httpResponse = $this->request($method, $url, $data, false);
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

        return $httpResponse;
    }


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


    protected function getDefaultHttpUserAgent(): string
    {
        return sprintf(
            'der-spiegel/ww-assets-client (https://github.com/DerSpiegel/ww_assets_php_client) PHP/%s',
            PHP_VERSION
        );
    }


    public function getHttpUserAgent(): string
    {
        return $this->httpUserAgent;
    }


    public function setHttpUserAgent(string $httpUserAgent): self
    {
        $this->httpUserAgent = $httpUserAgent;
        return $this;
    }


    private function reLogin(): bool
    {
        try {
            $this->getToken(true);
            return true;
        } catch (Exception) {
            return false;
        }
    }


    public function getToken(bool $force = false): string
    {
        return match ($this->authMethod) {
            self::AUTH_METHOD_BEARER_TOKEN => $this->getBearerToken($force),
            self::AUTH_METHOD_CSRF_TOKEN => $this->getCsrfToken($force),
            self::AUTH_METHOD_AUTHCRED => $this->getAuthCred(),
            default => throw new DomainException(
                sprintf(
                    "%s: Invalid Authentication method <%d>",
                    __METHOD__,
                    $this->authMethod
                )
            ),
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


    public function setBearerToken(string $bearerToken): void
    {
        $this->bearerToken = $bearerToken;
        $this->setAuthMethod(self::AUTH_METHOD_BEARER_TOKEN);
    }


    /**
     * Perform API login and return Authorization token
     */
    public function getBearerToken(bool $force = false): string
    {
        if ((strlen($this->bearerToken) > 0) && (!$force)) {
            return $this->bearerToken;
        }

        if (!$this->allowReLogin) {
            $e = new NotAuthorizedAssetsException(
                sprintf("%s: Not Authorized", __METHOD__),
                NotAuthorizedAssetsException::CODE
            );
            $this->health->setServiceIsAvailableByException($e);
            throw $e;
        }

        $response = ApiLoginRequest::createFromConfig($this)();

        if (!$response->loginSuccess) {
            $e = new NotAuthorizedAssetsException(
                sprintf('%s: Assets API login failed: %s', __METHOD__, $response->loginFaultMessage),
                NotAuthorizedAssetsException::CODE
            );
            $this->health->setServiceIsAvailableByException($e);
            throw $e;
        }

        if (strlen($response->authToken) === 0) {
            throw new AssetsException(
                sprintf('%s: Assets API login succeeded, but authToken is empty', __METHOD__)
            );
        }

        $this->bearerToken = 'Bearer ' . $response->authToken;

        return $this->bearerToken;
    }


    public function setCsrfToken(string $csrfToken, array $cookies = []): void
    {
        $this->csrfToken = $csrfToken;
        $this->cookies = $cookies;
        $this->setAuthMethod(self::AUTH_METHOD_CSRF_TOKEN);
    }


    public function getAuthCred(): string
    {
        return $this->authCred;
    }


    public function setAuthCred(string $authCred): void
    {
        $this->authCred = $authCred;
        $this->setAuthMethod(self::AUTH_METHOD_AUTHCRED);
    }


    public function getCookies(): array
    {
        return $this->cookies;
    }


    public function getCsrfToken(bool $force = false): string
    {
        if ((strlen($this->csrfToken) > 0) && (!$force)) {
            return $this->csrfToken;
        }

        if (!$this->allowReLogin) {
            $e = new NotAuthorizedAssetsException(
                sprintf("%s: Not Authorized", __METHOD__),
                NotAuthorizedAssetsException::CODE
            );
            $this->health->setServiceIsAvailableByException($e);
            throw $e;
        }

        $response = LoginRequest::createFromConfig($this)();

        if (!$response->loginSuccess) {
            $e = new NotAuthorizedAssetsException(
                sprintf(
                    '%s: Assets login failed: %s',
                    __METHOD__,
                    $response->loginFaultMessage
                ),
                NotAuthorizedAssetsException::CODE
            );
            $this->health->setServiceIsAvailableByException($e);
            throw $e;
        }

        if (strlen($response->csrfToken) === 0) {
            throw new AssetsException(sprintf('%s: Assets login succeeded, but csrfToken is empty', __METHOD__));
        }

        $this->csrfToken = $response->csrfToken;

        return $this->csrfToken;
    }


    public function logout(bool $cleanUpToken = true): LogoutResponse
    {
        $httpResponse = $this->serviceRequest('POST', 'logout');
        $logout = LogoutResponse::createFromHttpResponse($httpResponse);

        if ($cleanUpToken) {
            $this->bearerToken = '';
        }

        return $logout;
    }


    public function downloadFileToPath(string $url, string $targetPath): void
    {
        try {
            $httpResponse = $this->request('GET', $url, ['forceDownload' => 'true'], false);
        } catch (GuzzleException $e) {
            switch ($e->getCode()) {
                case 401: // Unauthorized
                    // TODO: prevent a possible loop here?

                    // re-login
                    if (!$this->reLogin()) {
                        throw $e;
                    }

                    // try again
                    $this->downloadFileToPath($url, $targetPath);
                    return;
                default:
                    // something went wrong
                    $this->logger->error(
                        sprintf(
                            '%s: GET request to <%s> failed: <%d> "%s"',
                            __METHOD__,
                            $url,
                            $e->getCode(),
                            $e->getMessage()
                        )
                    );
                    throw $e;
            }
        }

        $this->writeResponseBodyToPath($httpResponse, $targetPath);
    }


    public function writeResponseBodyToPath(ResponseInterface $httpResponse, string $targetPath): void
    {
        $fp = fopen($targetPath, 'wb');

        if ($fp === false) {
            throw new AssetsException(
                sprintf(
                    '%s: Failed to open <%s> for writing',
                    __METHOD__,
                    $targetPath
                )
            );
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
            throw new AssetsException(
                sprintf(
                    '%s: Failed to write HTTP response to <%s>',
                    __METHOD__,
                    $targetPath
                )
            );
        }
    }


    public function buildOriginalFileUrl(string $assetId): string
    {
        return "{$this->config->url}file/$assetId/*/$assetId";
    }
}
