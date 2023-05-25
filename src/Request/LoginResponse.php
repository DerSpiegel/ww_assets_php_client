<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use ReflectionClass;

/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268831-Assets-Server-REST-API-login
 */
class LoginResponse extends Response
{
    public function __construct(
        #[MapFromJson] readonly bool   $loginSuccess = false,
        #[MapFromJson] readonly string $loginFaultMessage = '',
        #[MapFromJson] readonly string $serverVersion = '',
        #[MapFromJson] readonly array  $userProfile = [],
        #[MapFromJson] readonly string $csrfToken = ''
    )
    {
    }


    public static function createFromJson(array $json): self
    {
        return (new ReflectionClass(static::class))->newInstanceArgs(self::applyJsonMapping($json));
    }
}
