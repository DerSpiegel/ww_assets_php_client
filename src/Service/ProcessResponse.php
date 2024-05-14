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
}
