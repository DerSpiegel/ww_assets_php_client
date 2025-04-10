<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268831-Assets-Server-REST-API-login
 */
class LoginRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly string $username = '',
        readonly string $password = '',
        readonly string $clientType = '',
        readonly bool $returnProfile = false
    ) {
        parent::__construct($assetsClient);
    }


    public static function createFromConfig(AssetsClient $assetsClient): LoginRequest
    {
        return new LoginRequest(
            $assetsClient,
            username: $assetsClient->config->username,
            password: $assetsClient->config->password
        );
    }


    /**
     * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268831-Assets-Server-REST-API-login
     */
    public function __invoke(): LoginResponse
    {
        $data = [
            'username' => $this->username,
            'password' => $this->password,
            'returnProfile' => $this->returnProfile ? 'true' : 'false'
        ];

        if (strlen($this->clientType) > 0) {
            $data['clientType'] = $this->clientType;
        }

        $httpResponse = $this->assetsClient->serviceRequest('POST', 'login', $data);

        return LoginResponse::createFromHttpResponse($httpResponse);
    }
}
