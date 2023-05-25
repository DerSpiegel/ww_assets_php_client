<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use ReflectionClass;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851432-Assets-Server-REST-API-search
 */
class SearchResponse extends Response
{
    public function __construct(
        readonly ?AssetResponseList   $hits = null,
        #[MapFromJson] readonly array $facets = [],
        #[MapFromJson] readonly int   $firstResult = 0,
        #[MapFromJson] readonly int   $maxResultHits = 0,
        #[MapFromJson] readonly int   $totalHits = 0,
    )
    {
    }


    protected static function applyJsonMapping(array $json): array
    {
        $result = parent::applyJsonMapping($json);

        if (isset($result['totalHits'])) {
            $result['totalHits'] =  max(0, $result['totalHits']);
        }

        if (isset($json['hits']) && is_array($json['hits'])) {
            $result['hits'] = new AssetResponseList();

            foreach ($json['hits'] as $hitJson) {
                $result['hits']->addValue(AssetResponse::createFromJson($hitJson));
            }
        }

        return $result;
    }


    public static function createFromJson(array $json): self
    {
        return (new ReflectionClass(static::class))->newInstanceArgs(self::applyJsonMapping($json));
    }
}
