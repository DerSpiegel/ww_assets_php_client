<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class SearchResponse
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851432-Assets-Server-REST-API-search
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class SearchResponse extends Response
{
    #[MapFromJson] protected array $facets = [];
    #[MapFromJson] protected int $firstResult = 0;
    #[MapFromJson] protected int $maxResultHits = 0;
    #[MapFromJson] protected int $totalHits = 0;

    /** @var AssetResponse[] */
    protected array $hits = [];


    /**
     * @param array $json
     * @return self
     */
    public function fromJson(array $json): self
    {
        parent::fromJson($json);

        if (isset($json['hits']) && is_array($json['hits'])) {
            $this->hits = [];

            foreach ($json['hits'] as $hitJson) {
                $this->hits[] = (new AssetResponse())->fromJson($hitJson);
            }
        }

        return $this;
    }


    /**
     * @return int
     */
    public function getFirstResult(): int
    {
        return $this->firstResult;
    }


    /**
     * @return int
     */
    public function getMaxResultHits(): int
    {
        return $this->maxResultHits;
    }


    /**
     * @return int
     */
    public function getTotalHits(): int
    {
        return max(0, $this->totalHits);
    }


    /**
     * @return AssetResponse[]
     */
    public function getHits(): array
    {
        return $this->hits;
    }


    /**
     * @return array
     */
    public function getFacets(): array
    {
        return $this->facets;
    }
}
