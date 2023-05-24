<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request\AssetResponse;
use DerSpiegel\WoodWingAssetsClient\Request\Request;
use DerSpiegel\WoodWingAssetsClient\Request\SearchRequest;


/**
 * Search for an asset by ID and return all or selected metadata
 */
class SearchAssetRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        public readonly string $assetId,
        public readonly array $metadataToReturn = []
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): AssetResponse
    {
        $response = (new SearchRequest($this->assetsClient,
            q: 'id:' . $this->assetId,
            metadataToReturn: empty($metadataToReturn) ? [SearchRequest::METADATA_TO_RETURN_DEFAULT] : $metadataToReturn
        ))();

        if ($response->getTotalHits() === 0) {
            throw new AssetsException(sprintf('%s: Asset with ID <%s> not found', __METHOD__, $this->assetId), 404);
        }

        if ($response->getTotalHits() > 1) {
            // god help us if this happens
            throw new AssetsException(sprintf('%s: Multiple assets with ID <%s> found', __METHOD__, $this->assetId), 404);
        }

        return $response->getHits()[0];
    }
}