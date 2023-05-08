<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Request\BrowseRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;


final class BrowseRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $request = (new BrowseRequest($this->assetsClient))
            ->setPath('/');

        $response = $request->execute();

        $this->assertGreaterThan(0, $response->getItems());
    }
}