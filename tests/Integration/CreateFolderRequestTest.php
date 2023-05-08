<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Request\CreateFolderRequest;
use DerSpiegel\WoodWingAssetsClient\Request\RemoveFolderRequest;
use DerSpiegel\WoodWingAssetsClient\Request\UpdateFolderRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


final class CreateFolderRequestTest extends IntegrationFixture
{
    public function testCreateFolder(): void
    {
        $folderPath = sprintf('%s/Subfolder to delete %s', IntegrationUtils::getAssetsTestsFolder(), uniqid());

        $folderResponse = (new CreateFolderRequest($this->assetsClient))
            ->setPath($folderPath)
            ->execute();

        $this->assertEquals($folderPath, $folderResponse->getPath());

        (new UpdateFolderRequest($this->assetsClient))
            ->setId($folderResponse->getId())
            ->setPath($folderPath)
            ->setMetadata(['description' => 'Test subfolder'])
            ->execute();

        (new RemoveFolderRequest($this->assetsClient))
            ->setId($folderResponse->getId())
            ->setPath($folderPath)
            ->execute();
    }
}