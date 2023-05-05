<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Request\CheckoutRequest;
use DerSpiegel\WoodWingAssetsClient\Request\UndoCheckoutRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class CheckoutRequestTest extends IntegrationFixture
{
    protected string $testAssetId;


    public function testCheckout(): void
    {
        $basename = sprintf('CheckoutRequestTest%s', uniqid());
        $filename = sprintf('%s.jpg', $basename);

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $this->testAssetId = $assetResponse->getId();
        $this->assertNotEmpty($this->testAssetId);

        $response = (new CheckoutRequest($this->assetsClient))
            ->setId($this->testAssetId)
            ->execute();

        $this->assertEquals(IntegrationUtils::getAssetsUsername(), $response->getCheckedOutBy());

        (new UndoCheckoutRequest($this->assetsClient))->setId($this->testAssetId)->execute();

        (new RemoveByIdRequest($this->assetsClient))->execute($this->testAssetId);
    }
}