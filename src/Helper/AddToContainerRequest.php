<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\RelationType;
use DerSpiegel\WoodWingAssetsClient\Request;
use DerSpiegel\WoodWingAssetsClient\Service\CreateRelationRequest;


/**
 * Add an asset to a collection
 */
class AddToContainerRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly AssetId $assetId,
        readonly AssetId $containerId
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): void
    {
        new CreateRelationRequest($this->assetsClient,
            relationType: RelationType::Contains,
            target1Id: $this->containerId,
            target2Id: $this->assetId
        )();
    }
}