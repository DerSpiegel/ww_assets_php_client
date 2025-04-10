<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Service\SearchRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use GuzzleHttp\Exception\ClientException;


final class SearchRequestTest extends IntegrationFixture
{
    public function testEmptySearch(): void
    {
        $response = new SearchRequest($this->assetsClient, q: '', num: 0)();

        $this->assertGreaterThan(0, $response->totalHits);
    }


    public function testMalformedQuery(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(400);

        new SearchRequest($this->assetsClient, q: 'noSuchField:', num: 0)();
    }


    public function testJsonQuery(): void
    {
        $json = '{"start":0,"num":300,"returnHighlightedText":true,"facets":{"extension":{"field":"extension"},"assetType":{"field":"assetType"},"assetDomain":{"field":"assetDomain"},"tags":{"field":"tags"},"status":{"field":"status"}},"sorting":[{"field":"assetCreated","descending":true}],"showAssetsOfSubfolders":"true","showSubCollections":"false","query":{"BoolQuery":{"elements":[{"operator":"MUST","query":{"QueryStringQuery":{"queryString":""}}},{"operator":"MUST_NOT","query":{"BoolQuery":{"elements":[{"operator":"MUST","query":{"TermQuery":{"name":"assetType","value":"collection"}}},{"operator":"MUST","query":{"WildcardQuery":{"name":"parentContainerIds","wildcardValue":"*"}}}]}}}]}},"maxResultHits":400,"firstResult":0,"returnThumbnailHits":true}';

        $response = new SearchRequest(
            $this->assetsClient,
            json: $json
        )();

        $this->assertGreaterThan(0, $response->totalHits);
    }
}