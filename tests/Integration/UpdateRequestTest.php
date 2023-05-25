<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\SearchAssetRequest;
use DerSpiegel\WoodWingAssetsClient\Request\UpdateRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class UpdateRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $filename = IntegrationUtils::getUniqueBasename(__CLASS__) .'.jpg';

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->id;
        $this->assertNotEmpty($assetId);

        $this->assertEmpty($assetResponse->metadata['headline'] ?? null);

        (new UpdateRequest($this->assetsClient,
            id: $assetId,
            metadata: ['headline' => $filename]
        ))();

        $updatedAssetResponse = (new SearchAssetRequest(
            $this->assetsClient,
            assetId: $assetId,
            metadataToReturn: ['headline']
        ))();

        $this->assertEquals($filename, $updatedAssetResponse->metadata['headline']);

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))();
    }
}