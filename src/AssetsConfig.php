<?php

namespace DerSpiegel\WoodWingAssetsClient;

use RuntimeException;


class AssetsConfig
{
    public function __construct(
        readonly string $url, // Must end with a slash
        readonly string $username,
        readonly string $password,
        readonly string $elasticsearchUrl = '',
        readonly bool   $verifySslCertificate = true
    )
    {
    }


    /**
     * Sanitizes parameters before calling the constructor
     */
    public static function create(
        string $url,
        string $username,
        string $password,
        string $elasticsearchUrl = '',
        bool   $verifySslCertificate = true
    ): self
    {
        $url = trim($url);

        // If the Assets URL doesn't end with a slash, append it
        if (($url !== '') && (!str_ends_with($url, '/'))) {
            $url .= '/';
        }

        $username = trim($username);
        $password = trim($password);
        $elasticsearchUrl = trim($elasticsearchUrl);

        return new AssetsConfig(
            $url,
            $username,
            $password,
            $elasticsearchUrl,
            $verifySslCertificate
        );
    }


    /**
     * Validate the configuration. Throw an exception if invalid.
     */
    public function validate(): void
    {
        if (strlen($this->url) === 0) {
            throw new RuntimeException(sprintf('%s: URL is empty.', __METHOD__));
        }

        if (strlen($this->username) === 0) {
            throw new RuntimeException(sprintf('%s: Username is empty.', __METHOD__));
        }

        if (strlen($this->password) === 0) {
            throw new RuntimeException(sprintf('%s: Password is empty.', __METHOD__));
        }
    }
}
