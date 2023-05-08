<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class RemoveByIdRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $filename = sprintf('%s%s.jpg', __CLASS__, uniqid());

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->getId();
        $this->assertNotEmpty($assetId);

        $response = (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))->execute();

        $this->assertEquals(1, $response->getProcessedCount());
    }
}