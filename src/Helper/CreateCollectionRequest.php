<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\Request\AssetResponse;
use DerSpiegel\WoodWingAssetsClient\Request\CreateRequest;
use DerSpiegel\WoodWingAssetsClient\Request\Request;


/**
 * Create a collection from assetPath (full path to collection, including .collection extension)
 */
class CreateCollectionRequest extends Request
{
    public function execute(string $assetPath, array $metadata = []): AssetResponse
    {
        $metadata['assetPath'] = $assetPath;

        return (new CreateRequest($this->assetsClient))
            ->setMetadata($metadata)
            ->execute();
    }
}