<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use BadFunctionCallException;
use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * List asset versions
 */
class VersionListRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly ?AssetId $assetId = null
    ) {
        parent::__construct($assetsClient);
    }


    public function validate(): void
    {
        if ($this->assetId === null) {
            throw new BadFunctionCallException(sprintf("%s: Asset ID is empty in VersionListRequest", __METHOD__));
        }
    }


    public function __invoke(): VersionListResponse
    {
        $this->validate();
        $isRawJsonRequest = !empty($this->json);

        $httpResponse = $this->assetsClient->serviceRequest(
            'POST',
            'version/list',
            $this->toArray()
        );

        return VersionListResponse::createFromHttpResponse($httpResponse);
    }


    protected function toArray(): array
    {
        return [
            'assetId' => $this->assetId->id
        ];
    }
}
