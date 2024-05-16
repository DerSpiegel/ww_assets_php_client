<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Service\PingRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;


final class PingRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $response = (new PingRequest($this->assetsClient, uid: uniqid(true)))();

        $this->assertEquals(200, $response->httpResponse->getStatusCode());
    }
}