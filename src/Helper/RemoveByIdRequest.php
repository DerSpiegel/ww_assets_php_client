<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\Request\ProcessResponse;
use DerSpiegel\WoodWingAssetsClient\Request\RemoveRequest;
use DerSpiegel\WoodWingAssetsClient\Request\Request;


/**
 * Remove asset by assetId
 */
class RemoveByIdRequest extends Request
{
    public function execute(string $assetId): ProcessResponse
    {
        return (new RemoveRequest($this->assetsClient))->setIds([$assetId])->execute();
    }
}