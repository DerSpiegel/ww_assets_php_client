<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class CreateRequestTest extends IntegrationFixture
{
    public function testCreate(): void
    {
        $basename = sprintf('CreateRequestTest%s', uniqid());
        $filename = sprintf('%s.jpg', $basename);

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->getId();
        $this->assertNotEmpty($assetId);

        $assetMetadata = $assetResponse->getMetadata();

        $this->assertEquals(IntegrationUtils::getAssetsUsername(), $assetMetadata['assetCreator']);
        $this->assertEquals($basename, $assetMetadata['baseName']);
        $this->assertEquals('image', $assetMetadata['assetDomain']);

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))->execute();
    }
}