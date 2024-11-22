<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use DerSpiegel\WoodWingAssetsClient\Service\PingRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use GuzzleHttp\Exception\ConnectException;
use Monolog\Logger;


final class ConnectExceptionTest extends IntegrationFixture
{
    public function test(): void
    {
        $assetsConfig = AssetsConfig::create(
            'https://assets.example.com',
            'username',
            'password'
        );

        $assetsClient = new AssetsClient($assetsConfig, new Logger('assetsClient'));

        $this->expectException(ConnectException::class);

        (new PingRequest($assetsClient, uid: uniqid(true)))();

        $this->assertEquals(false, $assetsClient->health->serviceIsAvailable());
    }
}