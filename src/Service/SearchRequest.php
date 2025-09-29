<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\RelationType;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Search for assets
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851432-Assets-Server-REST-API-search
 */
class SearchRequest extends Request
{
    const int START_DEFAULT = 0;
    const int NUM_DEFAULT = 50;
    const string SORT_DEFAULT = 'assetCreated-desc';
    const string METADATA_TO_RETURN_DEFAULT = 'all';
    const bool APPEND_REQUEST_SECRET_DEFAULT = false;
    const bool RETURN_HIGHLIGHTED_TEXT_DEFAULT = true;
    const bool RETURN_THUMBNAIL_HITS_DEFAULT = false;
    const string EXPAND_ORIGINAL_STORAGE_PATH = 'originalStoragePath';


    public function __construct(
        AssetsClient $assetsClient,
        readonly string $q = '',
        readonly int $start = self::START_DEFAULT,
        readonly int $num = self::NUM_DEFAULT,
        readonly array $sort = [self::SORT_DEFAULT],
        readonly array $metadataToReturn = [self::METADATA_TO_RETURN_DEFAULT],
        readonly array $facets = [],
        readonly bool $appendRequestSecret = self::APPEND_REQUEST_SECRET_DEFAULT,
        readonly bool $returnHighlightedText = self::RETURN_HIGHLIGHTED_TEXT_DEFAULT,
        readonly bool $returnThumbnailHits = self::RETURN_THUMBNAIL_HITS_DEFAULT,
        readonly string $json = '',
        readonly array $expand = [],
    ) {
        parent::__construct($assetsClient);
    }


    public function __invoke(): SearchResponse
    {
        $isRawJsonRequest = !empty($this->json);

        $httpResponse = $this->assetsClient->serviceRequest(
            'POST',
            'search',
            $this->toArray(),
            !$isRawJsonRequest
        );

        $this->logger->debug(
            'Search performed',
            [
                'method' => __METHOD__,
                'query' => $isRawJsonRequest ? $this->json : $this->q
            ]
        );

        return SearchResponse::createFromHttpResponse($httpResponse);
    }


    protected function toArray(): array
    {
        if (!empty($this->json)) {
            return json_decode($this->json, true, 512, JSON_THROW_ON_ERROR);
        }

        $params = [
            'q' => $this->q
        ];

        if ($this->start !== self::START_DEFAULT) {
            $params['start'] = $this->start;
        }

        if ($this->num !== self::NUM_DEFAULT) {
            $params['num'] = $this->num;
        }

        if ($this->sort !== [self::SORT_DEFAULT]) {
            $params['sort'] = implode(',', $this->sort);
        }

        if ($this->metadataToReturn !== [self::METADATA_TO_RETURN_DEFAULT]) {
            $params['metadataToReturn'] = implode(',', $this->metadataToReturn);
        }

        if (count($this->expand) > 0) {
            $params['expand'] = implode(',', $this->expand);
        }

        if (count($this->facets) > 0) {
            $params['facets'] = implode(',', $this->facets);
        }

        if ($this->appendRequestSecret !== self::APPEND_REQUEST_SECRET_DEFAULT) {
            $params['appendRequestSecret'] = $this->appendRequestSecret;
        }

        if ($this->returnHighlightedText !== self::RETURN_HIGHLIGHTED_TEXT_DEFAULT) {
            $params['returnHighlightedText'] = $this->returnHighlightedText;
        }

        if ($this->returnThumbnailHits !== self::RETURN_THUMBNAIL_HITS_DEFAULT) {
            $params['returnThumbnailHits'] = $this->returnThumbnailHits;
        }

        return $params;
    }


    /**
     * Get query for relation search
     *
     * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041854172#additional-queries
     */
    public static function getRelationSearchQ(
        string $relatedTo,
        string $relationTarget = '',
        ?RelationType $relationType = null
    ): string {
        $q = sprintf('relatedTo:%s', $relatedTo);

        if ($relationTarget !== '') {
            $q .= sprintf(' relationTarget:%s', $relationTarget);
        }

        if ($relationType !== null) {
            $q .= sprintf(' relationType:%s', $relationType->value);
        }

        return $q;
    }
}
