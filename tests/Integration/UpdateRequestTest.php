<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\SearchAssetRequest;
use DerSpiegel\WoodWingAssetsClient\Request\UpdateRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class UpdateRequestTest extends IntegrationFixture
{
    protected string $testAssetId;


    public function testUpdate(): void
    {
        $basename = sprintf('UpdateRequestTest%s', uniqid());
        $filename = sprintf('%s.jpg', $basename);

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $this->testAssetId = $assetResponse->getId();
        $this->assertNotEmpty($this->testAssetId);

        $this->assertEmpty($assetResponse->getMetadata()['headline'] ?? null);

        $request = (new UpdateRequest($this->assetsClient))
            ->setId($this->testAssetId)
            ->setMetadata(['headline' => $filename]);

        $request->execute();

        $updatedAssetResponse = (new SearchAssetRequest($this->assetsClient))
            ->execute($this->testAssetId, ['headline']);

        $this->assertEquals($filename, $updatedAssetResponse->getMetadata()['headline']);

        (new RemoveByIdRequest($this->assetsClient))->execute($this->testAssetId);
    }
}