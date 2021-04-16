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
    protected int $firstResult;

    protected int $maxResultHits;

    protected int $totalHits;

    /** @var AssetResponse[] */
    protected array $hits;

    protected array $facets;


    /**
     * @param array $json
     * @return self
     */
    public function fromJson(array $json): self
    {
        if (isset($json['firstResult'])) {
            $this->firstResult = $json['firstResult'];
        }

        if (isset($json['maxResultHits'])) {
            $this->maxResultHits = $json['maxResultHits'];
        }

        if (isset($json['totalHits'])) {
            $this->totalHits = $json['totalHits'];
        }

        if (isset($json['hits']) && is_array($json['hits'])) {
            $this->hits = [];

            foreach ($json['hits'] as $hitJson) {
                $this->hits[] = (new AssetResponse())->fromJson($hitJson);
            }
        }

        if (isset($json['facets']) && is_array($json['facets'])) {
            $this->facets = $json['facets'];
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
        return (is_array($this->hits) ? $this->hits : []);
    }


    /**
     * @return array
     */
    public function getFacets(): array
    {
        return (is_array($this->facets) ? $this->facets : []);
    }
}
