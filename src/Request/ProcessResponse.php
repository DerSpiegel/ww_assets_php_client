<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use ReflectionClass;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268731-Assets-Server-REST-API-copy
 */
class ProcessResponse extends Response
{
    public function __construct(
        #[MapFromJson] readonly int $processedCount = 0,
        #[MapFromJson] readonly int $errorCount = 0
    )
    {
    }


    public static function createFromJson(array $json): self
    {
        return (new ReflectionClass(static::class))->newInstanceArgs(self::applyJsonMapping($json));
    }
}
