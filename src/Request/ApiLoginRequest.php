<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\ElvisConfig;

/**
 * Class ApiLoginRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/115004785283-Elvis-6-REST-API-API-login
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class ApiLoginRequest extends Request
{
    protected string $username;

    protected string $password;

    protected string $clientId;


    /**
     * ApiLoginRequest constructor.
     * @param ElvisConfig $config
     */
    public function __construct(ElvisConfig $config)
    {
        parent::__construct($config);

        $this->setFromConfig($this->config);
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
        return $this->username ?: '';
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
        return $this->password ?: '';
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


    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId ?? '';
    }


    /**
     * @param string $clientId
     * @return self
     */
    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }
}
