<?php

namespace DerSpiegel\WoodWingAssetsClient;

use DerSpiegel\WoodWingAssetsClient\Exception\ElvisException;
use DerSpiegel\WoodWingAssetsClient\Exception\NotAuthorizedElvisException;
use DerSpiegel\WoodWingAssetsClient\Request\ApiLoginRequest;
use DerSpiegel\WoodWingAssetsClient\Request\ApiLoginResponse;
use DerSpiegel\WoodWingAssetsClient\Request\LoginRequest;
use DerSpiegel\WoodWingAssetsClient\Request\LoginResponse;
use DerSpiegel\WoodWingAssetsClient\Request\LogoutResponse;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;


/**
 * Class ElvisClientBase
 * @package DerSpiegel\WoodWingAssetsClient
 */
class ElvisClientBase
{
    const AUTH_METHOD_BEARER_TOKEN = 1;
    const AUTH_METHOD_CSRF_TOKEN = 2;
    const AUTH_METHOD_AUTHCRED = 3;

    const MAX_LOGIN_ATTEMPTS_PER_SECOND = 10;

    const RELATION_TARGET_ANY = 'any';
    const RELATION_TARGET_CHILD = 'child';
    const RELATION_TARGET_PARENT = 'parent';

    const RELATION_TYPE_CONTAINS = 'contains';
    const RELATION_TYPE_DUPLICATE = 'duplicate';
    const RELATION_TYPE_REFERENCES = 'references';
    const RELATION_TYPE_RELATED = 'related';
    const RELATION_TYPE_VARIATION = 'variation';

    protected ElvisConfig $config;

    protected LoggerInterface $logger;

    protected Client $httpClient;

    protected string $httpUserAgent = '';

    protected string $bearerToken = '';

    protected string $csrfToken = '';

    protected string $authCred = '';

    protected array $cookies = [];

    protected int $authMethod = self::AUTH_METHOD_BEARER_TOKEN;

    private array $loginAttempts = [];

    private bool $allowReLogin = true;

    protected int $requestTimeout = 60;


    /**
     * ElvisServer constructor.
     * @param ElvisConfig $config
     * @param LoggerInterface $logger
     */
    public function __construct(ElvisConfig $config, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;

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
     * @return ElvisConfig
     */
    public function getConfig(): ElvisConfig
    {
        return $this->config;
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
    protected function request(
        string $method,
        string $url,
        array $data = [],
        bool $multipart = true,
        bool $sendToken = true
    ): ResponseInterface {
        $options = [
            RequestOptions::HEADERS => ['User-Agent' => $this->getHttpUserAgent()],
            RequestOptions::TIMEOUT => $this->getRequestTimeout()
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
                    throw new RuntimeException("%s: Invalid Authentication method <%d>", __METHOD__, $this->authMethod);
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
            $response = $httpClient->request($method, $url, $options);

            // store cookies for further requests
            $this->cookies = $jar->toArray();

            return $response;
        } catch (GuzzleException $e) {
            // throw RuntimeException instead, to match the exception thrown by `ElvisServerBase::parseJsonResponse`
            throw new RuntimeException($e->getMessage(), $e->getCode());
        }
    }


    /**
     * @param string $service
     * @param array $data
     * @return array
     * @throws \JsonException
     */
    protected function serviceRequest(string $service, array $data = []): array
    {
        $httpResponse = $this->rawServiceRequest($service, $data);
        return ElvisUtils::parseJsonResponse($httpResponse->getBody());
    }


    /**
     * @param string $service
     * @param array $data
     * @return ResponseInterface
     */
    protected function rawServiceRequest(string $service, array $data = []): ResponseInterface
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
            // So when we get JSON back, run it through ElvisUtils::parseJsonResponse() which throws an exception on error.
            if (strpos($httpResponse->getHeaderLine('content-type'), 'application/json') === 0) {
                ElvisUtils::parseJsonResponse($httpResponse->getBody());
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
     * @throws \JsonException
     */
    protected function apiRequest(string $method, string $service, array $data = []): array
    {
        $url = sprintf(
            '%sapi/%s',
            $this->config->getUrl(),
            $service
        );

        try {
            $httpResponse = $this->request($method, $url, $data, false, true);
            return ElvisUtils::parseJsonResponse($httpResponse->getBody());
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
        return new Client();
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
        } catch (RuntimeException $e) {
            return false;
        }
    }


    /**
     * @param bool $force
     * @return string
     */
    public function getToken(bool $force = false): string
    {
        switch ($this->authMethod) {
            case self::AUTH_METHOD_BEARER_TOKEN:
                return $this->getBearerToken($force);
            case self::AUTH_METHOD_CSRF_TOKEN:
                return $this->getCsrfToken($force);
            case self::AUTH_METHOD_AUTHCRED:
                return $this->getAuthCred();
            default:
                throw new RuntimeException(sprintf("%s: Invalid Authentication method <%d>", __METHOD__,
                    $this->authMethod));
        }
    }


    private function preventLoginLoops()
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
            throw new NotAuthorizedElvisException(sprintf("%s: Not Authorized", __METHOD__), 401);
        }

        $request = new ApiLoginRequest($this->getConfig());

        $response = $this->apiLogin($request);

        if (!$response->isLoginSuccess()) {
            throw new RuntimeException(sprintf('%s: Elvis API login failed: %s', __METHOD__,
                $response->getLoginFaultMessage()));
        }

        if (strlen($response->getAuthToken()) === 0) {
            throw new RuntimeException(sprintf('%s: Elvis API login succeeded, but authToken is empty', __METHOD__));
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
    public function setAuthCred($authCred): void
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
            throw new NotAuthorizedElvisException(sprintf("%s: Not Authorized", __METHOD__), 401);
        }

        $request = new LoginRequest($this->getConfig());

        $response = $this->login($request);

        if (!$response->isLoginSuccess()) {
            throw new RuntimeException(sprintf('%s: Elvis login failed: %s', __METHOD__,
                $response->getLoginFaultMessage()));
        }

        if (strlen($response->getCsrfToken()) === 0) {
            throw new RuntimeException(sprintf('%s: Elvis login succeeded, but csrfToken is empty', __METHOD__));
        }

        $this->csrfToken = $response->getCsrfToken();

        return $this->csrfToken;
    }


    /**
     * @see https://helpcenter.woodwing.com/hc/en-us/articles/115004785283-Elvis-6-REST-API-API-login
     * @param ApiLoginRequest $request
     * @return ApiLoginResponse
     */
    public function apiLogin(ApiLoginRequest $request): ApiLoginResponse
    {
        $data = [
            'username' => $request->getUsername(),
            'password' => $request->getPassword()
        ];

        if (strlen($request->getClientId()) > 0) {
            $data['clientId'] = $request->getClientId();
        }

        try {
            $response = $this->serviceRequest('apilogin', $data);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf('%s: Login POST request failed', __METHOD__), $e->getCode(), $e);
        }

        return (new ApiLoginResponse())->fromJson($response);
    }


    /**
     * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002663443-Elvis-6-REST-API-login
     * @param LoginRequest $request
     * @return LoginResponse
     */
    public function login(LoginRequest $request): LoginResponse
    {
        $data = [
            'username' => $request->getUsername(),
            'password' => $request->getPassword(),
            'returnProfile' => $request->getReturnProfile() ? 'true' : 'false'
        ];

        if (strlen($request->getClientType()) > 0) {
            $data['clientType'] = $request->getClientType();
        }

        try {
            $response = $this->serviceRequest('login', $data);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf('%s: Login POST request failed', __METHOD__), $e->getCode(), $e);
        }

        return (new LoginResponse())->fromJson($response);
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
            throw new ElvisException(sprintf('%s: Failed to download <%s>: %s', __METHOD__, $url, $e->getMessage()),
                $e->getCode(), $e);
        }
    }


    /**
     * @param ResponseInterface $httpResponse
     * @param string $targetPath
     */
    protected function writeResponseBodyToPath(ResponseInterface $httpResponse, string $targetPath): void
    {
        $fp = fopen($targetPath, 'wb');

        if ($fp === false) {
            throw new ElvisException(sprintf('%s: Failed to open <%s> for writing', __METHOD__,
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
            throw new ElvisException(sprintf('%s: Failed to write HTTP response to <%s>', __METHOD__,
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
}
