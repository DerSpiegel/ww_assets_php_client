<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request\AssetResponse;
use DerSpiegel\WoodWingAssetsClient\Request\CreateRequest;
use DerSpiegel\WoodWingAssetsClient\Request\Request;


/**
 * Create a collection from assetPath (full path to collection, including .collection extension)
 */
class CreateCollectionRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        public readonly string $assetPath,
        public readonly array $metadata = []
    )
    {
        parent::__construct($assetsClient);
    }


    public function execute(): AssetResponse
    {
        $metadata = $this->metadata;
        $metadata['assetPath'] = $this->assetPath;

        return (new CreateRequest($this->assetsClient))
            ->setMetadata($metadata)
            ->execute();
    }
}