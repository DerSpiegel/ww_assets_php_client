<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Request\SearchRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;


final class SearchRequestTest extends IntegrationFixture
{
    public function testSearch(): void
    {
        $searchRequest = (new SearchRequest($this->assetsConfig))
            ->setQ('')
            ->setNum(0);

        $searchResponse = $this->assetsClient->search($searchRequest);

        $this->assertGreaterThan(0, $searchResponse->getTotalHits());
    }
}