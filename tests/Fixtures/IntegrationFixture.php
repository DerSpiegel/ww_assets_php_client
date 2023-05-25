<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Fixtures;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;


class IntegrationFixture extends TestCase
{
    protected AssetsConfig $assetsConfig;
    protected AssetsClient $assetsClient;


    protected function setUp(): void
    {
        $this->assetsConfig = AssetsConfig::create(
            ASSETS_URL,
            ASSETS_USERNAME,
            ASSETS_PASSWORD
        );

        $this->assetsClient = new AssetsClient($this->assetsConfig, new Logger('assetsClient'));
    }
}