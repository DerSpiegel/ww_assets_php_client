<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request\ProcessResponse;
use DerSpiegel\WoodWingAssetsClient\Request\RemoveRequest;
use DerSpiegel\WoodWingAssetsClient\Request\Request;


/**
 * Remove asset by assetId
 */
class RemoveByIdRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        public readonly string $assetId
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): ProcessResponse
    {
        return (new RemoveRequest($this->assetsClient))->setIds([$this->assetId])->execute();
    }
}