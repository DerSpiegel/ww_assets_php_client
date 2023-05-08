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
        $basename = sprintf('%s%s', __CLASS__, uniqid());
        $filename = sprintf('%s.jpg', $basename);

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->getId();
        $this->assertNotEmpty($assetId);

        $collectionResponse = (new CreateCollectionRequest(
            $this->assetsClient,
            assetPath: sprintf('%s/%s.collection', IntegrationUtils::getAssetsTestsFolder(), $basename)
        ))->execute();

        $collectionId = $collectionResponse->getId();

        (new AddToContainerRequest($this->assetsClient, assetId: $assetId, containerId: $collectionId))
            ->execute();

        $processResponse = (new RemoveFromContainerRequest(
            $this->assetsClient,
            assetId: $assetId,
            containerId: $collectionId
        ))->execute();

        $this->assertEquals(1, $processResponse->getProcessedCount());

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))->execute();
        (new RemoveByIdRequest($this->assetsClient, assetId: $collectionId))->execute();
    }
}