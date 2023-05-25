<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Request\BrowseRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;


final class BrowseRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $response = (new BrowseRequest($this->assetsClient, path: '/'))();

        $this->assertGreaterThan(0, $response->items);
    }
}