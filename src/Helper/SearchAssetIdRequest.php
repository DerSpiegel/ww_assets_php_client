<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request\Request;
use DerSpiegel\WoodWingAssetsClient\Request\SearchRequest;


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


    public function execute(): string
    {
        $response = (new SearchRequest($this->assetsClient))
            ->setQ($this->q)
            ->setNum(2)
            ->setMetadataToReturn([''])
            ->execute();

        if ($response->getTotalHits() === 0) {
            throw new AssetsException(sprintf('%s: No asset found for query <%s>', __METHOD__, $this->q), 404);
        }

        if (($response->getTotalHits() > 1) && $this->failIfMultipleHits) {
            throw new AssetsException(sprintf('%s: %d assets found for query <%s>', __METHOD__,
                $response->getTotalHits(), $this->q), 404);
        }

        return $response->getHits()[0]->getId();
    }
}