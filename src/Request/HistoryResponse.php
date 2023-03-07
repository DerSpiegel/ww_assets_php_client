<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class HistoryResponse
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042269011-Assets-Server-REST-API-Versioning-and-history
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class HistoryResponse extends Response
{
    #[MapFromJson] protected int $totalHits = 0;

    protected HistoryResponseItemList $hits;


    public function __construct()
    {
        $this->hits = new HistoryResponseItemList();
    }


    /**
     * @param array $json
     * @return self
     */
    public function fromJson(array $json): self
    {
        parent::fromJson($json);

        if (isset($json['hits']) && is_array($json['hits'])) {
            foreach ($json['hits'] as $hitJson) {
                $this->hits->addValue((new HistoryResponseItem())->fromJson($hitJson));
            }
        }

        return $this;
    }


    /**
     * @return int
     */
    public function getTotalHits(): int
    {
        return $this->totalHits;
    }


    /**
     * @return HistoryResponseItemList
     */
    public function getHits(): HistoryResponseItemList
    {
        return $this->hits;
    }
}
