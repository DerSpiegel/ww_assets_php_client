<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Request\CopyRequest;
use DerSpiegel\WoodWingAssetsClient\Request\MoveRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class MoveRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $sourceFilename = IntegrationUtils::getUniqueBasename(__CLASS__) . '.jpg';

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $sourceFilename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->getId();
        $this->assertNotEmpty($assetId);

        $source = sprintf('%s/%s', IntegrationUtils::getAssetsTestsFolder(), $sourceFilename);
        $target = sprintf('%s/Moved%s', IntegrationUtils::getAssetsTestsFolder(), $sourceFilename);

        $response = (new MoveRequest($this->assetsClient,
            source: $source,
            target: $target,
            fileReplacePolicy: CopyRequest::FILE_REPLACE_POLICY_THROW_EXCEPTION
        ))();

        $this->assertEquals(1, $response->getProcessedCount());

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))();
    }
}