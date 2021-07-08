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
    protected bool $loginSuccess = false;
    protected string $loginFaultMessage = '';
    protected string $serverVersion = '';
    protected string $authToken = '';


    /**
     * @param array $json
     * @return self
     */
    public function fromJson(array $json): self
    {
        if (isset($json['loginSuccess'])) {
            $this->loginSuccess = $json['loginSuccess'];
        }

        if (isset($json['loginFaultMessage'])) {
            $this->loginFaultMessage = $json['loginFaultMessage'];
        }

        if (isset($json['serverVersion'])) {
            $this->serverVersion = $json['serverVersion'];
        }

        if (isset($json['authToken'])) {
            $this->authToken = $json['authToken'];
        }

        return $this;
    }


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
