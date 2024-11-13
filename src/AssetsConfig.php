<?php

namespace DerSpiegel\WoodWingAssetsClient;

use BadFunctionCallException;


readonly class AssetsConfig
{
    public function __construct(
        public string $url, // Must end with a slash
        public string $username,
        public string $password,
        public string $elasticsearchUrl = '',
        public bool $verifySslCertificate = true,
        public ?AssetsHealth $health = null,
    ) {
    }


    /**
     * Sanitizes parameters before calling the constructor
     */
    public static function create(
        string $url,
        string $username,
        string $password,
        string $elasticsearchUrl = '',
        bool $verifySslCertificate = true,
        ?AssetsHealth $health = null,
    ): self {
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
            $verifySslCertificate,
            $health,
        );
    }


    /**
     * Validate the configuration. Throw an exception if invalid.
     */
    public function validate(): void
    {
        if (strlen($this->url) === 0) {
            throw new BadFunctionCallException(sprintf('%s: URL is empty.', __METHOD__));
        }

        if (strlen($this->username) === 0) {
            throw new BadFunctionCallException(sprintf('%s: Username is empty.', __METHOD__));
        }

        if (strlen($this->password) === 0) {
            throw new BadFunctionCallException(sprintf('%s: Password is empty.', __METHOD__));
        }
    }
}
