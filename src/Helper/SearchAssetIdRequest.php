<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request\Request;
use DerSpiegel\WoodWingAssetsClient\Request\SearchRequest;


/**
 * Search for an asset and return its ID
 */
class SearchAssetIdRequest extends Request
{
    public function execute(string $q, bool $failIfMultipleHits): string
    {
        $response = (new SearchRequest($this->assetsClient))
            ->setQ($q)
            ->setNum(2)
            ->setMetadataToReturn([''])
            ->execute();

        if ($response->getTotalHits() === 0) {
            throw new AssetsException(sprintf('%s: No asset found for query <%s>', __METHOD__, $q), 404);
        }

        if (($response->getTotalHits() > 1) && $failIfMultipleHits) {
            throw new AssetsException(sprintf('%s: %d assets found for query <%s>', __METHOD__,
                $response->getTotalHits(), $q), 404);
        }

        return $response->getHits()[0]->getId();
    }
}