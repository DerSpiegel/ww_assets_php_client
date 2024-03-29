<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\AddToContainerRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\CreateCollectionRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\RemoveFromContainerRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class AddToContainerRequestTest extends IntegrationFixture
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

        $collectionResponse = (new CreateCollectionRequest(
            $this->assetsClient,
            assetPath: sprintf('%s/%s.collection', IntegrationUtils::getAssetsTestsFolder(), $basename)
        ))();

        $collectionId = $collectionResponse->id;

        (new AddToContainerRequest($this->assetsClient,
            assetId: $assetId,
            containerId: $collectionId
        ))();

        $processResponse = (new RemoveFromContainerRequest($this->assetsClient,
            assetId: $assetId,
            containerId: $collectionId
        ))();

        $this->assertEquals(1, $processResponse->processedCount);

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))();
        (new RemoveByIdRequest($this->assetsClient, assetId: $collectionId))();
    }
}