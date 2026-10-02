<?php

namespace DerSpiegel\WoodWingAssetsClient;

use BadFunctionCallException;
use Uri\Rfc3986\Uri;


readonly class AssetsConfig
{
    final public function __construct(
        public Uri $url,
        public string $username,
        #[\SensitiveParameter] public string $password,
        public string $elasticsearchUrl = '',
        public bool $verifySslCertificate = true,
        public ?AssetsHealth $health = null,
        public ?string $httpUserAgent = null,
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
        ?string $httpUserAgent = null,
    ): static {
        if (trim($url) === '') {
            throw new BadFunctionCallException(sprintf('%s: URL is empty.', __METHOD__));
        }

        return new static(
            new Uri(trim($url)),
            trim($username),
            trim($password),
            trim($elasticsearchUrl),
            $verifySslCertificate,
            $health,
            $httpUserAgent,
        );
    }


    /**
     * Validate the configuration. Throw an exception if invalid.
     */
    public function validate(): void
    {
        if (strlen($this->username) === 0) {
            throw new BadFunctionCallException(sprintf('%s: Username is empty.', __METHOD__));
        }

        if (strlen($this->password) === 0) {
            throw new BadFunctionCallException(sprintf('%s: Password is empty.', __METHOD__));
        }
    }
}
