<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\ElvisConfig;


/**
 * Class SearchRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002690386-Elvis-6-REST-API-search
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class SearchRequest extends Request
{
    const START_DEFAULT = 0;
    const NUM_DEFAULT = 50;
    const SORT_DEFAULT = 'assetCreated-desc';
    const METADATA_TO_RETURN_DEFAULT = 'all';
    const APPEND_REQUEST_SECRET_DEFAULT = false;
    const RETURN_HIGHLIGHTED_TEXT_DEFAULT = true;

    protected string $q = '';

    protected int $start = self::START_DEFAULT;

    protected int $num = self::NUM_DEFAULT;

    /** @var string[] */
    protected array $sort = [self::SORT_DEFAULT];

    /** @var string[] */
    protected array $metadataToReturn = [self::METADATA_TO_RETURN_DEFAULT];

    /** @var string[] */
    protected array $facets = [];

    protected bool $appendRequestSecret = self::APPEND_REQUEST_SECRET_DEFAULT;

    protected bool $returnHighlightedText = self::RETURN_HIGHLIGHTED_TEXT_DEFAULT;


    /**
     * @return array
     */
    public function toArray(): array
    {
        $params = [
            'q' => $this->getQ()
        ];

        if ($this->getStart() !== self::START_DEFAULT) {
            $params['start'] = $this->getStart();
        }

        if ($this->getNum() !== self::NUM_DEFAULT) {
            $params['num'] = $this->getNum();
        }

        if ($this->getSort() !== [self::SORT_DEFAULT]) {
            $params['sort'] = implode(',', $this->getSort());
        }

        if ($this->getMetadataToReturn() !== [self::METADATA_TO_RETURN_DEFAULT]) {
            $params['metadataToReturn'] = implode(',', $this->getMetadataToReturn());
        }

        if (count($this->getFacets()) > 0) {
            $params['facets'] = implode(',', $this->getFacets());
        }

        if ($this->isAppendRequestSecret() !== self::APPEND_REQUEST_SECRET_DEFAULT) {
            $params['appendRequestSecret'] = $this->isAppendRequestSecret();
        }

        if ($this->isReturnHighlightedText() !== self::RETURN_HIGHLIGHTED_TEXT_DEFAULT) {
            $params['returnHighlightedText'] = $this->isReturnHighlightedText();
        }

        return $params;
    }


    /**
     * @return string
     */
    public function toQueryString(): string
    {
        return http_build_query($this->toArray());
    }


    /**
     * @return string
     */
    public function getQ(): string
    {
        return $this->q;
    }


    /**
     * @param string $q
     * @return self
     */
    public function setQ(string $q): self
    {
        $this->q = $q;
        return $this;
    }


    /**
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }


    /**
     * @param int $start
     * @return self
     */
    public function setStart(int $start): self
    {
        $this->start = $start;
        return $this;
    }


    /**
     * @return int
     */
    public function getNum(): int
    {
        return $this->num;
    }


    /**
     * @param int $num
     * @return self
     */
    public function setNum(int $num): self
    {
        $this->num = $num;
        return $this;
    }


    /**
     * @return array
     */
    public function getSort(): array
    {
        return $this->sort;
    }


    /**
     * @param array $sort
     * @return self
     */
    public function setSort(array $sort): self
    {
        $this->sort = $sort;
        return $this;
    }


    /**
     * @return string[]
     */
    public function getMetadataToReturn(): array
    {
        return $this->metadataToReturn;
    }


    /**
     * @param string[] $metadataToReturn
     * @return self
     */
    public function setMetadataToReturn(array $metadataToReturn): self
    {
        $this->metadataToReturn = $metadataToReturn;
        return $this;
    }


    /**
     * @return string[]
     */
    public function getFacets(): array
    {
        return $this->facets;
    }


    /**
     * @param string[] $facets
     * @return self
     */
    public function setFacets(array $facets): self
    {
        $this->facets = $facets;
        return $this;
    }


    /**
     * @return bool
     */
    public function isAppendRequestSecret(): bool
    {
        return $this->appendRequestSecret;
    }


    /**
     * @param bool $appendRequestSecret
     * @return self
     */
    public function setAppendRequestSecret(bool $appendRequestSecret): self
    {
        $this->appendRequestSecret = $appendRequestSecret;
        return $this;
    }


    /**
     * @return bool
     */
    public function isReturnHighlightedText(): bool
    {
        return $this->returnHighlightedText;
    }


    /**
     * @param bool $returnHighlightedText
     * @return self
     */
    public function setReturnHighlightedText(bool $returnHighlightedText): self
    {
        $this->returnHighlightedText = $returnHighlightedText;
        return $this;
    }
}
