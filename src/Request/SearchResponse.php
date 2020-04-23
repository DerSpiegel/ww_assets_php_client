<?php

namespace DerSpiegel\WoodWingElvisClient\Request;


/**
 * Class SearchResponse
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002690386-Elvis-6-REST-API-search
 * @package DerSpiegel\WoodWingElvisClient\Request
 */
class SearchResponse extends Response
{
    protected int $firstResult;

    protected int $maxResultHits;

    protected int $totalHits;

    /** @var AssetResponse[] */
    protected array $hits;


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

        return $this;
    }


    /**
     * @return int
     */
    public function getFirstResult(): int
    {
        return intval($this->firstResult);
    }


    /**
     * @return int
     */
    public function getMaxResultHits(): int
    {
        return intval($this->maxResultHits);
    }


    /**
     * @return int
     */
    public function getTotalHits(): int
    {
        return max(0, intval($this->totalHits));
    }


    /**
     * @return AssetResponse[]
     */
    public function getHits(): array
    {
        return (is_array($this->hits) ? $this->hits : []);
    }
}