<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\AddToContainerRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\CreateCollectionRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class AddToContainerRequestTest extends IntegrationFixture
{
    public function testAddToContainer(): void
    {
        $basename = sprintf('AddToContainerRequestTest%s', uniqid());
        $filename = sprintf('%s.jpg', $basename);

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->getId();
        $this->assertNotEmpty($assetId);

        $collectionResponse = (new CreateCollectionRequest($this->assetsClient))->execute(
            sprintf('%s/%s.collection', IntegrationUtils::getAssetsTestsFolder(), $basename)
        );

        $collectionId = $collectionResponse->getId();

        (new AddToContainerRequest($this->assetsClient))
            ->execute($assetId, $collectionId);

        (new RemoveByIdRequest($this->assetsClient))->execute($assetId);
        (new RemoveByIdRequest($this->assetsClient))->execute($collectionId);
    }
}