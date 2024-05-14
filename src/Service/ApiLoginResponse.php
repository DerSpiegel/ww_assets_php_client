<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsUtils;
use DerSpiegel\WoodWingAssetsClient\MapFromJson;
use DerSpiegel\WoodWingAssetsClient\Response;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851192-Assets-Server-REST-API-API-login
 */
class ApiLoginResponse extends Response
{
    public function __construct(
        readonly ?ResponseInterface    $httpResponse = null,
        #[MapFromJson] readonly bool   $loginSuccess = false,
        #[MapFromJson] readonly string $loginFaultMessage = '',
        #[MapFromJson] readonly string $serverVersion = '',
        #[MapFromJson] readonly string $authToken = ''
    )
    {
    }


    public static function createFromJson(array $json, ?ResponseInterface $httpResponse = null): self
    {
        $args = self::applyJsonMapping($json);

        if ($httpResponse !== null) {
            $args['httpResponse'] = $httpResponse;
        }

        return (new ReflectionClass(static::class))->newInstanceArgs($args);
    }


    public static function createFromHttpResponse(ResponseInterface $httpResponse): self
    {
        return self::createFromJson(AssetsUtils::parseJsonResponse($httpResponse->getBody()), $httpResponse);
    }
}
