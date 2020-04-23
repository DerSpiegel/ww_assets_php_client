<?php


namespace DerSpiegel\WoodWingElvisClient\Request;


/**
 * Class LoginResponse
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002663443-Elvis-6-REST-API-login
 * @package DerSpiegel\WoodWingElvisClient\Request
 */
class LoginResponse extends Response
{
    protected bool $loginSuccess;

    protected string $loginFaultMessage;

    protected string $serverVersion;

    // TODO: implement the userProfile model??
    protected array $userProfile;

    protected string $csrfToken;


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

        if (isset($json['userProfile'])) {
            $this->csrfToken = $json['userProfile'];
        }

        if (isset($json['csrfToken'])) {
            $this->csrfToken = $json['csrfToken'];
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isLoginSuccess(): bool
    {
        return boolval($this->loginSuccess);
    }


    /**
     * @return string
     */
    public function getLoginFaultMessage(): string
    {
        return $this->loginFaultMessage ?: '';
    }


    /**
     * @return string
     */
    public function getServerVersion(): string
    {
        return $this->serverVersion ?: '';
    }


    /**
     * @return array
     */
    public function getUserProfile(): array
    {
        return $this->userProfile ?: [];
    }


    /**
     * @return string
     */
    public function getCsrfToken(): string
    {
        return $this->csrfToken ?: '';
    }
}
