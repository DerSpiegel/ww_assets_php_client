<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;
use DerSpiegel\WoodWingAssetsClient\Service\ProcessResponse;
use DerSpiegel\WoodWingAssetsClient\Service\RemoveRequest;


/**
 * Remove asset by assetId
 */
class RemoveByIdRequest extends Request
{
    public function __construct(
        AssetsClient     $assetsClient,
        readonly AssetId $assetId,
        readonly bool    $async = false
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): ProcessResponse
    {
        return new RemoveRequest($this->assetsClient, ids: [$this->assetId], async: $this->async)();
    }
}