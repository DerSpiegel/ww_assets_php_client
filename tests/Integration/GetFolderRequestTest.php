<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Request\GetFolderRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


final class GetFolderRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $folderPath = IntegrationUtils::getAssetsTestsFolder();

        $response = (new GetFolderRequest($this->assetsClient))
            ->setPath($folderPath)
            ->execute();

        $this->assertEquals($folderPath, $response->getPath());
    }
}