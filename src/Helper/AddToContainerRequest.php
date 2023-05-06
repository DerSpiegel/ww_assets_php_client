<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\RelationType;
use DerSpiegel\WoodWingAssetsClient\Request\CreateRelationRequest;
use DerSpiegel\WoodWingAssetsClient\Request\Request;


/**
 * Add an asset to a collection
 */
class AddToContainerRequest extends Request
{
    public function execute(string $assetId, string $containerId): void
    {
        (new CreateRelationRequest($this->assetsClient))
            ->setRelationType(RelationType::Contains)
            ->setTarget1Id($containerId)
            ->setTarget2Id($assetId)
            ->execute();
    }
}