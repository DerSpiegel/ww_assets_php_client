<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Request\CheckoutRequest;
use DerSpiegel\WoodWingAssetsClient\Request\UndoCheckoutRequest;
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

        $assetId = $assetResponse->getId();
        $this->assertNotEmpty($assetId);

        $response = (new CheckoutRequest($this->assetsClient, id: $assetId))();

        $this->assertEquals(IntegrationUtils::getAssetsUsername(), $response->getCheckedOutBy());

        (new UndoCheckoutRequest($this->assetsClient))->setId($assetId)->execute();

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))->execute();
    }
}