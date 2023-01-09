<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Request\RemoveRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class CreateRequestTest extends IntegrationFixture
{
    protected string $testAssetId;


    public function testCreate(): void
    {
        $basename = sprintf('CreateRequestTest%s', uniqid());
        $filename = sprintf('%s.jpg', $basename);

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => ASSETS_TESTS_FOLDER]
        );

        $this->testAssetId = $assetResponse->getId();
        $this->assertNotEmpty($this->testAssetId);

        $assetMetadata = $assetResponse->getMetadata();

        $this->assertEquals(ASSETS_USERNAME, $assetMetadata['assetCreator']);
        $this->assertEquals($basename, $assetMetadata['baseName']);
        $this->assertEquals('image', $assetMetadata['assetDomain']);

        $request = (new RemoveRequest($this->assetsConfig))
            ->setIds([$this->testAssetId]);

        $this->assetsClient->removeAsset($request);
    }
}