<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Service\PromoteRequest;
use DerSpiegel\WoodWingAssetsClient\Service\UpdateRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class PromoteRequestTest extends IntegrationFixture
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

        $tmpFilename = sprintf('/tmp/%s-v2.jpg', $basename);
        file_put_contents($tmpFilename, base64_decode(IntegrationUtils::getTinyJpegData()));
        $fp = fopen($tmpFilename, 'r');

        (new UpdateRequest($this->assetsClient,
            id: $assetId,
            filedata: $fp
        ))();

        (new PromoteRequest($this->assetsClient,
            id: $assetId,
            version: 1
        ))();

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))();
    }
}