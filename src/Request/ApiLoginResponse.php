<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class ApiLoginResponse
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851192-Assets-Server-REST-API-API-login
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class ApiLoginResponse extends Response
{
    #[MapFromJson] protected bool $loginSuccess = false;
    #[MapFromJson] protected string $loginFaultMessage = '';
    #[MapFromJson] protected string $serverVersion = '';
    #[MapFromJson] protected string $authToken = '';


    /**
     * @return bool
     */
    public function isLoginSuccess(): bool
    {
        return $this->loginSuccess;
    }


    /**
     * @return string
     */
    public function getLoginFaultMessage(): string
    {
        return $this->loginFaultMessage;
    }


    /**
     * @return string
     */
    public function getServerVersion(): string
    {
        return $this->serverVersion;
    }


    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }
}
