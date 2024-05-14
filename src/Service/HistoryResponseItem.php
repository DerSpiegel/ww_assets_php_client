<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\Response;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042269011-Assets-Server-REST-API-Versioning-and-history
 */
class HistoryResponseItem extends Response
{
    public function __construct(
        readonly ?AssetResponse $hit = null,
        readonly ?UsageStatsRecord $usageStatsRecord = null
    )
    {
    }


    protected static function applyJsonMapping(array $json): array
    {
        $result = [];

        if (isset($json['hit']) && is_array($json['hit'])) {
            $result['hit'] = AssetResponse::createFromJson($json['hit']);
        }

        if (isset($json['usageStatsRecord']) && is_array($json['usageStatsRecord'])) {
            $result['usageStatsRecord'] = UsageStatsRecord::createFromJson($json['usageStatsRecord']);
        }

        return $result;
    }
}
