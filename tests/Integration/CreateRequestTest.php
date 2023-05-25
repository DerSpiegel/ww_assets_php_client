<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class CreateRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $basename = IntegrationUtils::getUniqueBasename(__CLASS__);
        $filename = sprintf('%s.jpg', $basename);

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->id;
        $this->assertNotEmpty($assetId);

        $assetMetadata = $assetResponse->metadata;

        $this->assertEquals(IntegrationUtils::getAssetsUsername(), $assetMetadata['assetCreator']);
        $this->assertEquals($basename, $assetMetadata['baseName']);
        $this->assertEquals('image', $assetMetadata['assetDomain']);

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))();
    }
}