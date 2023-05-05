<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Request\SearchRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;


final class SearchRequestTest extends IntegrationFixture
{
    public function testSearch(): void
    {
        $request = (new SearchRequest($this->assetsClient))
            ->setQ('')
            ->setNum(0);

        $response = $request->execute();

        $this->assertGreaterThan(0, $response->getTotalHits());
    }
}