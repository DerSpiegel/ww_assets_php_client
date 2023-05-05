<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request\AssetResponse;
use DerSpiegel\WoodWingAssetsClient\Request\Request;
use DerSpiegel\WoodWingAssetsClient\Request\SearchRequest;


/**
 * Search for an asset by ID and return all or selected metadata
 */
class SearchAssetRequest extends Request
{
    public function execute(string $assetId, array $metadataToReturn = []): AssetResponse
    {
        $request = (new SearchRequest($this->assetsClient))
            ->setQ('id:' . $assetId);

        if (!empty($metadataToReturn)) {
            $request->setMetadataToReturn($metadataToReturn);
        }

        $response = $request->execute();

        if ($response->getTotalHits() === 0) {
            throw new AssetsException(sprintf('%s: Asset with ID <%s> not found', __METHOD__, $assetId), 404);
        }

        if ($response->getTotalHits() > 1) {
            // god help us if this happens
            throw new AssetsException(sprintf('%s: Multiple assets with ID <%s> found', __METHOD__, $assetId), 404);
        }

        return $response->getHits()[0];
    }
}