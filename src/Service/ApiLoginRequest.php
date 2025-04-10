<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851192-Assets-Server-REST-API-API-login
 */
class ApiLoginRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly string $username = '',
        readonly string $password = '',
        readonly string $clientId = ''
    ) {
        parent::__construct($assetsClient);
    }


    public static function createFromConfig(AssetsClient $assetsClient): ApiLoginRequest
    {
        return new ApiLoginRequest(
            $assetsClient,
            username: $assetsClient->config->username,
            password: $assetsClient->config->password
        );
    }


    /**
     * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851192-Assets-Server-REST-API-API-login
     */
    public function __invoke(): ApiLoginResponse
    {
        $data = [
            'username' => $this->username,
            'password' => $this->password
        ];

        if (strlen($this->clientId) > 0) {
            $data['clientId'] = $this->clientId;
        }

        $httpResponse = $this->assetsClient->serviceRequest('POST', 'apilogin', $data);

        return ApiLoginResponse::createFromHttpResponse($httpResponse);
    }
}
