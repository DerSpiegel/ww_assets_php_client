<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\SearchAssetRequest;
use DerSpiegel\WoodWingAssetsClient\Request\UpdateBulkRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class UpdateBulkRequestTest extends IntegrationFixture
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

        $this->assertEmpty($assetResponse->getMetadata()['headline'] ?? null);

        $request = (new UpdateBulkRequest($this->assetsClient))
            ->setQ(sprintf('id:%s', $assetId))
            ->setMetadata(['headline' => $filename]);

        $request->execute();

        $updatedAssetResponse = (new SearchAssetRequest(
            $this->assetsClient,
            assetId: $assetId,
            metadataToReturn: ['headline']
        ))->execute();

        $this->assertEquals($filename, $updatedAssetResponse->getMetadata()['headline']);

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))->execute();
    }
}