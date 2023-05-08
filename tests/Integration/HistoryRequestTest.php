<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Request\HistoryRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class HistoryRequestTest extends IntegrationFixture
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

        (new HistoryRequest($this->assetsClient))
            ->setId($assetId)
            ->execute();

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))->execute();
    }
}