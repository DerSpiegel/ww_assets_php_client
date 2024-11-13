<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Service\SearchRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use GuzzleHttp\Exception\ClientException;


final class SearchRequestTest extends IntegrationFixture
{
    public function testEmptySearch(): void
    {
        $response = (new SearchRequest($this->assetsClient, q: '', num: 0))();

        $this->assertGreaterThan(0, $response->totalHits);
    }


    public function testMalformedQuery(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(400);

        (new SearchRequest($this->assetsClient, q: 'noSuchField:', num: 0))();
    }
}