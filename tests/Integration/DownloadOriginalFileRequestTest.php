<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\DownloadOriginalFileRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class DownloadOriginalFileRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $filename = IntegrationUtils::getUniqueBasename(__CLASS__) . '.jpg';

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $targetPath = sprintf('%s/%s', sys_get_temp_dir(), $filename);

        new DownloadOriginalFileRequest(
            $this->assetsClient,
            targetPath: $targetPath,
            assetResponse: $assetResponse
        )();

        $this->assertTrue(file_exists($targetPath));
        $this->assertGreaterThan(0, filesize($targetPath));

        new RemoveByIdRequest($this->assetsClient, assetId: $assetResponse->id)();
    }
}