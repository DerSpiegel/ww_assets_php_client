<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\RelationType;
use DerSpiegel\WoodWingAssetsClient\Request\CreateRelationRequest;
use DerSpiegel\WoodWingAssetsClient\Request\Request;


/**
 * Add an asset to a collection
 */
class AddToContainerRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        public readonly string $assetId,
        public readonly string $containerId
    )
    {
        parent::__construct($assetsClient);
    }


    public function execute(): void
    {
        (new CreateRelationRequest($this->assetsClient))
            ->setRelationType(RelationType::Contains)
            ->setTarget1Id($this->containerId)
            ->setTarget2Id($this->assetId)
            ->execute();
    }
}