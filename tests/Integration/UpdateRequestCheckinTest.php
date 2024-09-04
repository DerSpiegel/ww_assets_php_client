<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\DownloadOriginalFileRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Helper\SearchAssetRequest;
use DerSpiegel\WoodWingAssetsClient\Service\ApiLoginRequest;
use DerSpiegel\WoodWingAssetsClient\Service\AssetResponse;
use DerSpiegel\WoodWingAssetsClient\Service\UpdateRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class UpdateRequestCheckinTest extends IntegrationFixture
{
    public function setUp(): void
    {
        parent::setUp();

        $response = ApiLoginRequest::createFromConfig($this->assetsClient)();

        if (version_compare($response->serverVersion, '6.107', '<')) {
            $this->markTestSkipped('This test requires Assets Server 6.107 or higher');
        }
    }


    public function test(): void
    {
        $basename = IntegrationUtils::getUniqueBasename(__CLASS__);

        // Create

        $descriptionOnCreate = 'Description from CREATE';
        $assetResponse = $this->createAsset($basename, $descriptionOnCreate);

        $assetId = $assetResponse->id;
        $this->assertNotEmpty($assetId);

        $this->assertEquals(IntegrationUtils::getAssetsUsername(), $assetResponse->metadata['assetCreator'], 'Wrong assetCreator after create');
        $this->assertEquals($basename, $assetResponse->metadata['baseName'], 'Wrong baseName after create');
        $this->assertEquals($descriptionOnCreate, $assetResponse->metadata['description'], 'Wrong description after create');
        $this->assertEquals('image', $assetResponse->metadata['assetDomain'], 'Wrong assetDomain after create');
        $this->assertEquals(1, $assetResponse->metadata['versionNumber'], 'Wrong versionNumber after create');

        // Download v1 file ("Description from CREATE" has been embedded into the file by Assets)

        $tmpFileV1 = sprintf('%s/%s_v1.jpg', sys_get_temp_dir(), IntegrationUtils::getUniqueBasename(__CLASS__));

        (new DownloadOriginalFileRequest($this->assetsClient,
            targetPath: $tmpFileV1,
            assetResponse: $assetResponse
        ))();

        // Update description

        $descriptionAfterUpdate = 'Description from UPDATE';

        (new UpdateRequest($this->assetsClient,
            id: $assetResponse->id,
            metadata: ['description' => $descriptionAfterUpdate],
        ))();

        $assetResponse = (new SearchAssetRequest($this->assetsClient, $assetResponse->id))();

        $this->assertEquals(1, $assetResponse->metadata['versionNumber'], 'Wrong versionNumber after update');
        $this->assertEquals($descriptionAfterUpdate, $assetResponse->metadata['description'], 'Wrong description after update');

        // Checkin with keepMetadata=true

        $assetResponse = $this->updateAssetFile($assetResponse, $tmpFileV1, true);

        $this->assertEquals(2, $assetResponse->metadata['versionNumber'], 'Wrong versionNumber after checkin with keepMetadata=true');
        $this->assertEquals($descriptionAfterUpdate, $assetResponse->metadata['description'], 'Wrong description after checkin with keepMetadata=true');

        // Checkin with keepMetadata=false

        $assetResponse = $this->updateAssetFile($assetResponse, $tmpFileV1, false);

        $this->assertEquals(3, $assetResponse->metadata['versionNumber'], 'Wrong versionNumber after checkin with keepMetadata=false');
        $this->assertEquals($descriptionOnCreate, $assetResponse->metadata['description'], 'Wrong description after checkin with keepMetadata=false');

        // Cleanup

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))();
    }


    protected function createAsset(string $basename, string $setDescription): AssetResponse
    {
        $filename = sprintf('%s.jpg', $basename);

        return IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            [
                'folderPath' => IntegrationUtils::getAssetsTestsFolder(),
                'description' => $setDescription,
            ]
        );
    }


    protected function updateAssetFile(AssetResponse $assetResponse, string $tmpFile, bool $keepMetadata): AssetResponse
    {
        (new UpdateRequest($this->assetsClient,
            id: $assetResponse->id,
            filedata: fopen($tmpFile, 'r'),
            keepMetadata: $keepMetadata
        ))();

        return (new SearchAssetRequest($this->assetsClient,
            assetId: $assetResponse->id,
            metadataToReturn: ['description','versionNumber']
        ))();
    }
}
