<?php

namespace integration;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use DerSpiegel\WoodWingAssetsClient\Request\SearchRequest;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;


final class SearchRequestTest extends TestCase
{
    protected AssetsConfig $assetsConfig;
    protected AssetsClient $assetsClient;


    protected function setUp(): void
    {
        $this->assetsConfig = new AssetsConfig(
            ASSETS_URL,
            ASSETS_USERNAME,
            ASSETS_PASSWORD
        );

        $this->assetsClient = new AssetsClient($this->assetsConfig, new Logger('assetsClient'));
    }


    public function testSearch(): void
    {
        $searchRequest = (new SearchRequest($this->assetsConfig))
            ->setQ('')
            ->setNum(0);

        $searchResponse = $this->assetsClient->search($searchRequest);

        $this->assertGreaterThan(0, $searchResponse->getTotalHits());
    }
}