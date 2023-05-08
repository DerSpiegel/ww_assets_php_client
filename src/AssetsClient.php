<?php

namespace DerSpiegel\WoodWingAssetsClient;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Exception\NotAuthorizedAssetsException;
use DerSpiegel\WoodWingAssetsClient\Request\ApiLoginRequest;
use DerSpiegel\WoodWingAssetsClient\Request\AssetResponse;
use DerSpiegel\WoodWingAssetsClient\Request\CheckoutRequest;
use DerSpiegel\WoodWingAssetsClient\Request\CheckoutResponse;
use DerSpiegel\WoodWingAssetsClient\Request\CopyRequest;
use DerSpiegel\WoodWingAssetsClient\Request\CreateFolderRequest;
use DerSpiegel\WoodWingAssetsClient\Request\CreateRelationRequest;
use DerSpiegel\WoodWingAssetsClient\Request\CreateRequest;
use DerSpiegel\WoodWingAssetsClient\Request\FolderResponse;
use DerSpiegel\WoodWingAssetsClient\Request\GetFolderRequest;
use DerSpiegel\WoodWingAssetsClient\Request\HistoryDetailLevel;
use DerSpiegel\WoodWingAssetsClient\Request\HistoryRequest;
use DerSpiegel\WoodWingAssetsClient\Request\HistoryResponse;
use DerSpiegel\WoodWingAssetsClient\Request\LoginRequest;
use DerSpiegel\WoodWingAssetsClient\Request\LogoutResponse;
use DerSpiegel\WoodWingAssetsClient\Request\MoveRequest;
use DerSpiegel\WoodWingAssetsClient\Request\ProcessResponse;
use DerSpiegel\WoodWingAssetsClient\Request\PromoteRequest;
use DerSpiegel\WoodWingAssetsClient\Request\RemoveFolderRequest;
use DerSpiegel\WoodWingAssetsClient\Request\RemoveRelationRequest;
use DerSpiegel\WoodWingAssetsClient\Request\RemoveRequest;
use DerSpiegel\WoodWingAssetsClient\Request\SearchRequest;
use DerSpiegel\WoodWingAssetsClient\Request\SearchResponse;
use DerSpiegel\WoodWingAssetsClient\Request\UndoCheckoutRequest;
use DerSpiegel\WoodWingAssetsClient\Request\UpdateBulkRequest;
use DerSpiegel\WoodWingAssetsClient\Request\UpdateFolderRequest;
use DerSpiegel\WoodWingAssetsClient\Request\UpdateRequest;
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
use RuntimeException;
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


    /**
     * AssetsClientBase constructor.
     * @param AssetsConfig $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected AssetsConfig $config,
        protected LoggerInterface $logger
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
     * @return AssetsConfig
     */
    public function getConfig(): AssetsConfig
    {
        return $this->config;
    }


    public function getLogger(): LoggerInterface
    {
        return $this->logger;
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
     * @param bool $multipart - weather to send the data as multipart or application/json
     * @param bool $sendToken
     * @return ResponseInterface
     * @throws RuntimeException
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
            RequestOptions::VERIFY => $this->getConfig()->isVerifySslCertificate()
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
                    throw new RuntimeException(sprintf("%s: Invalid Authentication method <%d>", __METHOD__, $this->authMethod));
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
                    $url = sprintf("%s?%s", $url, http_build_query($data));
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
            // throw RuntimeException instead, to match the exception thrown by `AssetsServerBase::parseJsonResponse`
            throw new RuntimeException($e->getMessage(), $e->getCode());
        }
    }


    /**
     * @param string $service
     * @param array $data
     * @return array
     * @throws JsonException
     */
    public function serviceRequest(string $service, array $data = []): array
    {
        $httpResponse = $this->rawServiceRequest($service, $data);
        return AssetsUtils::parseJsonResponse($httpResponse->getBody());
    }


    /**
     * @param string $service
     * @param array $data
     * @return ResponseInterface
     */
    public function rawServiceRequest(string $service, array $data = []): ResponseInterface
    {
        $url = sprintf(
            '%sservices/%s',
            $this->config->getUrl(),
            $service
        );

        $loginRequest = in_array($service, ['login', 'apilogin']);

        if ($loginRequest) {
            $this->preventLoginLoops();
        }

        try {
            $httpResponse = $this->request('POST', $url, $data, true, !$loginRequest);

            // Even usually-binary responses like "checkout and download" return JSON on error (i.e. "not logged in").
            // So when we get JSON back, run it through AssetsUtils::parseJsonResponse() which throws an exception on error.
            if (str_starts_with($httpResponse->getHeaderLine('content-type'), 'application/json')) {
                AssetsUtils::parseJsonResponse($httpResponse->getBody());
            }
        } catch (RuntimeException $e) {
            switch ($e->getCode()) {
                case 401: // Unauthorized
                    // TODO: prevent a possible loop here?

                    // re-login
                    if (!$this->reLogin()) {
                        throw $e;
                    }

                    // try again
                    return $this->rawServiceRequest($service, $data);
                default:
                    // something went wrong
                    throw $e;
            }
        }

        return $httpResponse;
    }


    /**
     * @param string $method
     * @param string $service
     * @param array $data
     * @return array
     * @throws JsonException
     */
    public function apiRequest(string $method, string $service, array $data = []): array
    {
        $url = sprintf(
            '%sapi/%s',
            $this->config->getUrl(),
            $service
        );

        try {
            $httpResponse = $this->request($method, $url, $data, false);

            $responseBbody = (string)$httpResponse->getBody();

            if (empty($responseBbody)) {
                return [];
            }

            return AssetsUtils::parseJsonResponse($responseBbody);
        } catch (RuntimeException $e) {
            switch ($e->getCode()) {
                case 401: // Unauthorized
                    // TODO: prevent a possible loop here?

                    // re-login
                    if (!$this->reLogin()) {
                        throw $e;
                    }

                    // try again
                    return $this->apiRequest($method, $service, $data);
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
            'der-spiegel/ww-elvis-client (https://github.com/DerSpiegel/ww_elvis_php_client) PHP/%s',
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
        } catch (RuntimeException) {
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
            default => throw new RuntimeException(sprintf("%s: Invalid Authentication method <%d>", __METHOD__,
                $this->authMethod)),
        };
    }


    private function preventLoginLoops(): void
    {
        $key = time();
        $this->loginAttempts[$key] = ($this->loginAttempts[$key] ?? 0) + 1;
        if ($this->loginAttempts[$key] > self::MAX_LOGIN_ATTEMPTS_PER_SECOND) {
            throw new RuntimeException(sprintf("%s: MAX_LOGIN_ATTEMPTS_PER_SECOND exceeded", __METHOD__));
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
            throw new NotAuthorizedAssetsException(sprintf("%s: Not Authorized", __METHOD__), 401);
        }

        $response = (new ApiLoginRequest($this))->execute();

        if (!$response->isLoginSuccess()) {
            throw new RuntimeException(sprintf('%s: Assets API login failed: %s', __METHOD__,
                $response->getLoginFaultMessage()));
        }

        if (strlen($response->getAuthToken()) === 0) {
            throw new RuntimeException(sprintf('%s: Assets API login succeeded, but authToken is empty', __METHOD__));
        }

        $this->bearerToken = 'Bearer ' . $response->getAuthToken();

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
            throw new NotAuthorizedAssetsException(sprintf("%s: Not Authorized", __METHOD__), 401);
        }

        $response = (new LoginRequest($this))->execute();

        if (!$response->isLoginSuccess()) {
            throw new RuntimeException(sprintf('%s: Assets login failed: %s', __METHOD__,
                $response->getLoginFaultMessage()));
        }

        if (strlen($response->getCsrfToken()) === 0) {
            throw new RuntimeException(sprintf('%s: Assets login succeeded, but csrfToken is empty', __METHOD__));
        }

        $this->csrfToken = $response->getCsrfToken();

        return $this->csrfToken;
    }


    /**
     * @param bool $cleanUpToken
     * @return LogoutResponse
     */
    public function logout(bool $cleanUpToken = true): LogoutResponse
    {
        try {
            $httpResponse = $this->serviceRequest('logout');
            $logout = (new LogoutResponse())->fromJson($httpResponse);

            if ($cleanUpToken) {
                $this->bearerToken = '';
            }

            return $logout;

        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf('%s: Logout POST request failed', __METHOD__), $e->getCode(), $e);
        }
    }


    /**
     * @param string $url
     * @param string $targetPath
     */
    protected function downloadFileToPath(string $url, string $targetPath): void
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
        return "{$this->config->getUrl()}file/$assetId/*/$assetId";
    }


    /** Helper methods not part of the Assets REST API */


    /**
     * @param AssetResponse $assetResponse
     * @param string $targetPath
     * @return void
     */
    public function downloadOriginalFile(AssetResponse $assetResponse, string $targetPath): void
    {
        if (strlen($assetResponse->getOriginalUrl()) === 0) {
            throw new AssetsException(sprintf('%s: Original URL is empty', __METHOD__), 404);
        }

        $this->downloadFileToPath($assetResponse->getOriginalUrl(), $targetPath);

        $this->logger->debug(sprintf('Original file of <%s> downloaded to <%s>', $assetResponse->getId(), $targetPath),
            [
                'method' => __METHOD__,
                'assetId' => $assetResponse->getId()
            ]
        );
    }


    /**
     * @param AssetResponse $assetResponse
     * @param string $targetPath
     */
    public function downloadOriginalFileById(AssetResponse $assetResponse, string $targetPath): void
    {
        // TODO: Deprecate or fix; should be "byId" and expect a string $assetId

        $originalUrl = $assetResponse->getOriginalUrl();

        $this->downloadFileToPath($originalUrl, $targetPath);

        $this->logger->debug(sprintf('Original File Downloaded <%s>', $originalUrl),
            [
                'method' => __METHOD__,
                'assetId' => $assetResponse->getId()
            ]
        );
    }
}
