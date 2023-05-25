<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Request\CreateFolderRequest;
use DerSpiegel\WoodWingAssetsClient\Request\RemoveFolderRequest;
use DerSpiegel\WoodWingAssetsClient\Request\UpdateFolderRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


final class CreateFolderRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $folderPath = sprintf('%s/Subfolder to delete %s', IntegrationUtils::getAssetsTestsFolder(), uniqid());

        $folderResponse = (new CreateFolderRequest($this->assetsClient, path: $folderPath))();

        $this->assertEquals($folderPath, $folderResponse->path);

        (new UpdateFolderRequest($this->assetsClient,
            id: $folderResponse->id,
            path: $folderPath,
            metadata: ['description' => 'Test subfolder']
        ))();

        (new RemoveFolderRequest($this->assetsClient,
            id: $folderResponse->id,
            path: $folderPath
        ))();
    }
}