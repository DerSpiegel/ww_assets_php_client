<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Service\HistoryRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class HistoryRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $filename = IntegrationUtils::getUniqueBasename(__CLASS__) . '.jpg';

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->id;
        $this->assertNotEmpty($assetId);

        new HistoryRequest($this->assetsClient, id: $assetId)();

        new RemoveByIdRequest($this->assetsClient, assetId: $assetId)();
    }
}