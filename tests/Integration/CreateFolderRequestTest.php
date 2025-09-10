<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Api\CreateFolderRequest;
use DerSpiegel\WoodWingAssetsClient\Api\RemoveFolderRequest;
use DerSpiegel\WoodWingAssetsClient\Api\UpdateFolderRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


final class CreateFolderRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $folderPath = sprintf('%s/Subfolder to delete %s', IntegrationUtils::getAssetsTestsFolder(), uniqid());

        $folderResponse = new CreateFolderRequest($this->assetsClient, path: $folderPath)();

        $this->assertEquals($folderPath, $folderResponse->path);

        new UpdateFolderRequest($this->assetsClient,
            id: $folderResponse->id,
            path: $folderPath,
            metadata: ['description' => 'Test subfolder']
        )();

        new RemoveFolderRequest($this->assetsClient,
            id: $folderResponse->id,
            path: $folderPath
        )();
    }
}