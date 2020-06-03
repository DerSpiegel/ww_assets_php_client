<?php


namespace DerSpiegel\WoodWingElvisClient\Request;

use DerSpiegel\WoodWingElvisClient\ElvisConfig;


/**
 *
 * Class LoginRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002663443-Elvis-6-REST-API-login
 * @package DerSpiegel\WoodWingElvisClient\Request
 */
class LoginRequest extends Request
{
    protected string $username = '';

    protected string $password = '';

    protected string $clientType = '';

    protected bool $returnProfile = false;


    /**
     * LoginRequest constructor.
     * @param ElvisConfig $config
     */
    public function __construct(ElvisConfig $config)
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
     * @param ElvisConfig $config
     */
    protected function setFromConfig(ElvisConfig $config): void
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
