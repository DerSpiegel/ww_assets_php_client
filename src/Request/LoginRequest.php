<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use RuntimeException;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268831-Assets-Server-REST-API-login
 */
class LoginRequest extends Request
{
    protected string $username = '';
    protected string $password = '';
    protected string $clientType = '';
    protected bool $returnProfile = false;


    public function __construct(
        protected AssetsClient $assetsClient
    )
    {
        parent::__construct($assetsClient);

        $this->setFromConfig($this->assetsConfig);
    }


    /**
     * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268831-Assets-Server-REST-API-login
     */
    public function execute(): LoginResponse
    {
        $data = [
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'returnProfile' => $this->getReturnProfile() ? 'true' : 'false'
        ];

        if (strlen($this->getClientType()) > 0) {
            $data['clientType'] = $this->getClientType();
        }

        try {
            $response = $this->assetsClient->serviceRequest('login', $data);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf('%s: Login POST request failed', __METHOD__), $e->getCode(), $e);
        }

        return (new LoginResponse())->fromJson($response);
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
