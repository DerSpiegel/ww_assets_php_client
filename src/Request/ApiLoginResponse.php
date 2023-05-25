<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use ReflectionClass;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851192-Assets-Server-REST-API-API-login
 */
class ApiLoginResponse extends Response
{
    public function __construct(
        #[MapFromJson] readonly bool   $loginSuccess = false,
        #[MapFromJson] readonly string $loginFaultMessage = '',
        #[MapFromJson] readonly string $serverVersion = '',
        #[MapFromJson] readonly string $authToken = ''
    )
    {
    }


    public static function createFromJson(array $json): self
    {
        return (new ReflectionClass(static::class))->newInstanceArgs(self::applyJsonMapping($json));
    }
}
