<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Service\SearchRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;


final class SearchRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $response = (new SearchRequest($this->assetsClient, q: '', num: 0))();

        $this->assertGreaterThan(0, $response->totalHits);
    }
}