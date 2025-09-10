<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Service\CheckoutRequest;
use DerSpiegel\WoodWingAssetsClient\Service\UndoCheckoutRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class CheckoutRequestTest extends IntegrationFixture
{
    public function test(): void
    {
        $filename = IntegrationUtils::getUniqueBasename(__CLASS__) . '.jpg';

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->id;
        $this->assertNotEmpty($assetId);

        $response = new CheckoutRequest($this->assetsClient, id: $assetId)();

        $this->assertEquals(IntegrationUtils::getAssetsUsername(), $response->checkedOutBy);

        new UndoCheckoutRequest($this->assetsClient, id: $assetId)();

        new RemoveByIdRequest($this->assetsClient, assetId: $assetId)();
    }
}