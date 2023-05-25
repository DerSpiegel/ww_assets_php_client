<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request;
use DerSpiegel\WoodWingAssetsClient\Service\SearchRequest;


/**
 * Search for an asset and return its ID
 */
class SearchAssetIdRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        public readonly string $q,
        public readonly bool $failIfMultipleHits
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): string
    {
        $response = (new SearchRequest($this->assetsClient,
            q: $this->q,
            num: 2,
            metadataToReturn: ['']
        ))();

        if ($response->totalHits === 0) {
            throw new AssetsException(sprintf('%s: No asset found for query <%s>', __METHOD__, $this->q), 404);
        }

        if (($response->totalHits > 1) && $this->failIfMultipleHits) {
            throw new AssetsException(sprintf('%s: %d assets found for query <%s>', __METHOD__,
                $response->totalHits, $this->q), 404);
        }

        return $response->hits[0]->id;
    }
}