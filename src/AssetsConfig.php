<?php

namespace DerSpiegel\WoodWingAssetsClient;

use RuntimeException;


/**
 * Class AssetsConfig
 * @package DerSpiegel\WoodWingAssetsClient
 */
class AssetsConfig
{
    protected string $username = '';
    protected string $password = '';
    protected string $url = '';
    protected string $elasticsearchUrl = '';


    /**
     * AssetsConfig constructor.
     * @param string $url
     * @param string $username
     * @param string $password
     * @param string $elasticsearchUrl
     */
    public function __construct(string $url, string $username, string $password, string $elasticsearchUrl = '')
    {
        $this->url = trim($url);

        // If the Assets URL doesn't end with a slash, append it
        if (($this->url !== '') && (substr($this->url, -1) !== '/')) {
            $this->url .= '/';
        }

        $this->username = trim($username);
        $this->password = trim($password);
        $this->elasticsearchUrl = trim($elasticsearchUrl);
    }


    /**
     * @return string Base, absolute Assets URL, ends with a slash
     */
    public function getUrl(): string
    {
        return $this->url;
    }


    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }


    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    /**
     * @return string
     */
    public function getElasticsearchUrl(): string
    {
        return $this->elasticsearchUrl;
    }


    /**
     * Validate the configuration. Throw an exception if invalid.
     */
    public function validate(): void
    {
        if (strlen($this->getUrl()) === 0) {
            throw new RuntimeException(sprintf('%s: URL is empty.', __METHOD__));
        }

        if (strlen($this->getUsername()) === 0) {
            throw new RuntimeException(sprintf('%s: Username is empty.', __METHOD__));
        }

        if (strlen($this->getPassword()) === 0) {
            throw new RuntimeException(sprintf('%s: Password is empty.', __METHOD__));
        }
    }
}
