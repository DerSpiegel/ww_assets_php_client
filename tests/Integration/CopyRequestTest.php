<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\SearchAssetIdRequest;
use DerSpiegel\WoodWingAssetsClient\Request\CopyRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class CopyRequestTest extends IntegrationFixture
{
    public function testCopy(): void
    {
        $sourceFilename = sprintf('CopyRequestTest%s.jpg', uniqid());

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $sourceFilename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->getId();
        $this->assertNotEmpty($assetId);

        $source = sprintf('%s/%s', IntegrationUtils::getAssetsTestsFolder(), $sourceFilename);
        $target = sprintf('%s/CopyOf%s', IntegrationUtils::getAssetsTestsFolder(), $sourceFilename);

        $response = (new CopyRequest($this->assetsClient))
            ->setSource($source)
            ->setTarget($target)
            ->setFileReplacePolicy(CopyRequest::FILE_REPLACE_POLICY_THROW_EXCEPTION)
            ->execute();

        $this->assertEquals(1, $response->getProcessedCount());

        $targetId = (new SearchAssetIdRequest(
            $this->assetsClient,
            q: sprintf('assetPath:"%s"', $target),
            failIfMultipleHits: true
        ))->execute();

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))->execute();
        (new RemoveByIdRequest($this->assetsClient, assetId: $targetId))->execute();
    }
}