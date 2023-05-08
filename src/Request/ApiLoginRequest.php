<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use RuntimeException;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851192-Assets-Server-REST-API-API-login
 */
class ApiLoginRequest extends Request
{
    protected string $username = '';
    protected string $password = '';
    protected string $clientId = '';


    public function __construct(
        AssetsClient $assetsClient
    )
    {
        parent::__construct($assetsClient);

        $this->setFromConfig($this->assetsClient->getConfig());
    }


    /**
     * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851192-Assets-Server-REST-API-API-login
     */
    public function execute(): ApiLoginResponse
    {
        $data = [
            'username' => $this->getUsername(),
            'password' => $this->getPassword()
        ];

        if (strlen($this->getClientId()) > 0) {
            $data['clientId'] = $this->getClientId();
        }

        try {
            $response = $this->assetsClient->serviceRequest('apilogin', $data);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf('%s: Login POST request failed', __METHOD__), $e->getCode(), $e);
        }

        return (new ApiLoginResponse())->fromJson($response);
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


    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
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
