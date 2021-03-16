<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsConfig;


/**
 *
 * Class LoginRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268831-Assets-Server-REST-API-login
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class LoginRequest extends Request
{
    protected string $username = '';

    protected string $password = '';

    protected string $clientType = '';

    protected bool $returnProfile = false;


    /**
     * LoginRequest constructor.
     * @param AssetsConfig $config
     */
    public function __construct(AssetsConfig $config)
    {
        parent::__construct($config);

        $this->setFromConfig($this->config);
    }


    /**
     * @return string
     */
    public function getClientType(): string
    {
        return $this->clientType;
    }


    /**
     * @param string $clientType
     * @return self
     */
    public function setClientType(string $clientType): self
    {
        $this->clientType = $clientType;
        return $this;
    }


    /**
     * @return bool
     */
    public function getReturnProfile(): bool
    {
        return $this->returnProfile;
    }


    /**
     * @param bool $returnProfile
     * @return self
     */
    public function setReturnProfile(bool $returnProfile): self
    {
        $this->returnProfile = $returnProfile;
        return $this;
    }


    /**
     * @param AssetsConfig $config
     */
    protected function setFromConfig(AssetsConfig $config): void
    {
        $this->setUsername($config->getUsername());
        $this->setPassword($config->getPassword());
    }


    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }


    /**
     * @param string $username
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }


    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    /**
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}
