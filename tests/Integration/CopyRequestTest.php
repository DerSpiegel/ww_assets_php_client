<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\SearchAssetIdRequest;
use DerSpiegel\WoodWingAssetsClient\Service\CopyRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class CopyRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $sourceFilename = IntegrationUtils::getUniqueBasename(__CLASS__) . '.jpg';

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $sourceFilename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->id;
        $this->assertNotEmpty($assetId);

        $source = sprintf('%s/%s', IntegrationUtils::getAssetsTestsFolder(), $sourceFilename);
        $target = sprintf('%s/CopyOf%s', IntegrationUtils::getAssetsTestsFolder(), $sourceFilename);

        $response = new CopyRequest(
            $this->assetsClient,
            source: $source,
            target: $target,
            fileReplacePolicy: CopyRequest::FILE_REPLACE_POLICY_THROW_EXCEPTION
        )();

        $this->assertEquals(1, $response->processedCount);

        $targetId = new SearchAssetIdRequest(
            $this->assetsClient,
            q: sprintf('assetPath:"%s"', $target),
            failIfMultipleHits: true
        )();

        new RemoveByIdRequest($this->assetsClient, assetId: $assetId)();
        new RemoveByIdRequest($this->assetsClient, assetId: $targetId)();
    }
}