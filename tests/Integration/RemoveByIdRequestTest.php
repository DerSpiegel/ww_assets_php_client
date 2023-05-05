<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class RemoveByIdRequestTest extends IntegrationFixture
{
    protected string $testAssetId;


    public function testRemoveById(): void
    {
        $basename = sprintf('RemoveByIdRequestTest%s', uniqid());
        $filename = sprintf('%s.jpg', $basename);

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $this->testAssetId = $assetResponse->getId();
        $this->assertNotEmpty($this->testAssetId);

        $response = (new RemoveByIdRequest($this->assetsClient))->execute($this->testAssetId);

        $this->assertEquals(1, $response->getProcessedCount());
    }
}