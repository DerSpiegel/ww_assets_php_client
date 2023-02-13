<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class LoginResponse
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268831-Assets-Server-REST-API-login
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class LoginResponse extends Response
{
    #[MapFromJson] protected bool $loginSuccess = false;
    #[MapFromJson] protected string $loginFaultMessage = '';
    #[MapFromJson] protected string $serverVersion = '';
    #[MapFromJson] protected array $userProfile = [];
    #[MapFromJson] protected string $csrfToken = '';


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
     * @return array
     */
    public function getUserProfile(): array
    {
        return $this->userProfile;
    }


    /**
     * @return string
     */
    public function getCsrfToken(): string
    {
        return $this->csrfToken;
    }
}
