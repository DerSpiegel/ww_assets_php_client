<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request;
use DerSpiegel\WoodWingAssetsClient\Service\AssetResponse;
use DerSpiegel\WoodWingAssetsClient\Service\SearchRequest;


/**
 * Search for an asset by ID and return all or selected metadata
 */
class SearchAssetRequest extends Request
{
    public function __construct(
        AssetsClient     $assetsClient,
        readonly AssetId $assetId,
        readonly array   $metadataToReturn = []
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): AssetResponse
    {
        $response = new SearchRequest($this->assetsClient,
            q: 'id:' . $this->assetId->id,
            metadataToReturn: empty($this->metadataToReturn) ? [SearchRequest::METADATA_TO_RETURN_DEFAULT] : $this->metadataToReturn
        )();

        if ($response->totalHits === 0) {
            throw new AssetsException(sprintf('%s: Asset with ID <%s> not found', __METHOD__, $this->assetId->id), 404);
        }

        if ($response->totalHits > 1) {
            // god help us if this happens
            throw new AssetsException(sprintf('%s: Multiple assets with ID <%s> found', __METHOD__, $this->assetId), 404);
        }

        return $response->hits[0];
    }
}