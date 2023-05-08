<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Integration;

use DerSpiegel\WoodWingAssetsClient\Helper\RemoveByIdRequest;
use DerSpiegel\WoodWingAssetsClient\Request\PromoteRequest;
use DerSpiegel\WoodWingAssetsClient\Request\UpdateRequest;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationFixture;
use DerSpiegel\WoodWingAssetsClientTests\Fixtures\IntegrationUtils;


class PromoteRequestTest extends IntegrationFixture
{
    public function testPromote(): void
    {
        $basename = sprintf('PromoteRequestTest%s', uniqid());
        $filename = sprintf('%s.jpg', $basename);

        $assetResponse = IntegrationUtils::createJpegAsset(
            $this->assetsClient,
            $filename,
            ['folderPath' => IntegrationUtils::getAssetsTestsFolder()]
        );

        $assetId = $assetResponse->getId();
        $this->assertNotEmpty($assetId);

        $tmpFilename = sprintf('/tmp/%s-v2.jpg', $basename);
        file_put_contents($tmpFilename, base64_decode(IntegrationUtils::getTinyJpegData()));
        $fp = fopen($tmpFilename, 'r');

        $request = (new UpdateRequest($this->assetsClient))
            ->setId($assetId)
            ->setFiledata($fp);

        $request->execute();

        (new PromoteRequest($this->assetsClient))
            ->setId($assetId)
            ->setVersion(1)
            ->execute();

        (new RemoveByIdRequest($this->assetsClient, assetId: $assetId))->execute();
    }
}