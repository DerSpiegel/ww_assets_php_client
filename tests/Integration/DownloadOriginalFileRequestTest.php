<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\DownloadOriginalFileRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class DownloadOriginalFileRequestTest extends IntegrationFixture
{
    public function testDownloadOriginalFile(): void
    {
        $basename = sprintf('CreateRequestTest%s', uniqid());
        $filename = sprintf('%s.jpg', $basename);

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $targetPath = sprintf('%s/%s', sys_get_temp_dir(), $filename);

        (new DownloadOriginalFileRequest(
            $this->assetsClient,
            targetPath: $targetPath,
            assetResponse: $assetResponse
        ))->execute();

        $this->assertTrue(file_exists($targetPath));
        $this->assertGreaterThan(0, filesize($targetPath));

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetResponse->getId()))->execute();
    }
}