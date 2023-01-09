<?php

namespace integration;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use DerSpiegel\WoodWingAssetsClient\Request\CreateRequest;
use DerSpiegel\WoodWingAssetsClient\Request\RemoveRequest;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;


class CreateRequestTest extends TestCase
{
    protected AssetsConfig $assetsConfig;
    protected AssetsClient $assetsClient;
    protected string $testAssetId;


    protected function setUp(): void
    {
        $this->assetsConfig = new AssetsConfig(
            ASSETS_URL,
            ASSETS_USERNAME,
            ASSETS_PASSWORD
        );

        $this->assetsClient = new AssetsClient($this->assetsConfig, new Logger('assetsClient'));
    }


    public function testCreate(): void
    {
        $basename = 'CreateRequestTest' . uniqid();
        $filename = sprintf('/tmp/%s.jpg', $basename);

        file_put_contents($filename, base64_decode($this->getTestImageData()));

        $fp = fopen($filename, 'r');

        $request = (new CreateRequest($this->assetsConfig))
            ->setFiledata($fp)
            ->setMetadata(['folderPath' => ASSETS_TESTS_FOLDER]);

        $assetResponse = $this->assetsClient->create($request);
        $this->testAssetId = $assetResponse->getId();
        $this->assertNotEmpty($this->testAssetId);

        $assetMetadata = $assetResponse->getMetadata();

        $this->assertEquals(ASSETS_USERNAME, $assetMetadata['assetCreator']);
        $this->assertEquals($basename, $assetMetadata['baseName']);
        $this->assertEquals('image', $assetMetadata['assetDomain']);
    }


    public function tearDown(): void
    {
        $request = (new RemoveRequest($this->assetsConfig))
            ->setIds([$this->testAssetId]);

        $this->assetsClient->removeAsset($request);
    }


    /**
     * @see https://gist.github.com/scotthaleen/32f76a413e0dfd4b4d79c2a534d49c0b#file-tiny-jpg
     * @return string
     */
    protected function getTestImageData(): string
    {
        $imageDate = <<<EOT
/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA=
EOT;
        return trim($imageDate);
    }
}