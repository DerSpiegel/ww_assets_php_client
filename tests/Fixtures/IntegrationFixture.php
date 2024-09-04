<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Fixtures;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
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

        $logger = new Logger('assetsClient');

        // Enable for debugging (displays log lines on STDOUT):
        // $logger->pushHandler(new StreamHandler('php://stdout', Level::Debug));

        $this->assetsClient = new AssetsClient($this->assetsConfig, $logger);
    }
}