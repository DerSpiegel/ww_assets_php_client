<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use ReflectionClass;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268851-Assets-Server-REST-API-logout
 */
class LogoutResponse extends Response
{
    public function __construct(
        #[MapFromJson] readonly bool $logoutSuccess = false
    )
    {
    }


    public static function createFromJson(array $json): self
    {
        return (new ReflectionClass(static::class))->newInstanceArgs(self::applyJsonMapping($json));
    }
}
