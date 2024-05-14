<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsUtils;
use DerSpiegel\WoodWingAssetsClient\MapFromJson;
use DerSpiegel\WoodWingAssetsClient\Response;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268731-Assets-Server-REST-API-copy
 */
class ProcessResponse extends Response
{
    public function __construct(
        readonly ?ResponseInterface    $httpResponse = null,
        #[MapFromJson] readonly int    $processedCount = 0,
        #[MapFromJson] readonly int    $errorCount = 0,
        #[MapFromJson] readonly string $processId = ''
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
