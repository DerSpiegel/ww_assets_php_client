<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class HistoryResponseItem
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042269011-Assets-Server-REST-API-Versioning-and-history
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class HistoryResponseItem extends Response
{
    protected ?AssetResponse $hit = null;
    protected UsageStatsRecord $usageStatsRecord;


    /**
     * @param array $json
     * @return self
     */
    public function fromJson(array $json): self
    {
        parent::fromJson($json);

        if (isset($json['hit']) && is_array($json['hit'])) {
            $this->hit = (new AssetResponse())->fromJson($json['hit']);
        }

        if (isset($json['usageStatsRecord']) && is_array($json['usageStatsRecord'])) {
            $this->usageStatsRecord = (new UsageStatsRecord())->fromJson($json['usageStatsRecord']);
        }

        return $this;
    }


    /**
     * @return AssetResponse|null
     */
    public function getHit(): ?AssetResponse
    {
        return $this->hit;
    }


    /**
     * @return UsageStatsRecord
     */
    public function getUsageStatsRecord(): UsageStatsRecord
    {
        return $this->usageStatsRecord;
    }
}
