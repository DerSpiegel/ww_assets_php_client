<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Fixtures;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Service\AssetResponse;
use DerSpiegel\WoodWingAssetsClient\Service\CreateRequest;


class IntegrationUtils
{
    public static function getAssetsUsername(): string
    {
        return ASSETS_USERNAME;
    }


    public static function getAssetsTestsFolder(): string
    {
        return ASSETS_TESTS_FOLDER;
    }


    public static function getUniqueBasename(string $className): string
    {
        return sprintf('%s%s', strtr($className, '\\', '-'), uniqid());
    }


    public static function createJpegAsset(AssetsClient $assetsClient, string $filename, array $metadata): AssetResponse
    {
        $tmpFilename = sprintf('/tmp/%s', $filename);

        file_put_contents($tmpFilename, base64_decode(self::getTinyJpegData()));

        $fp = fopen($tmpFilename, 'r');

        return (new CreateRequest($assetsClient,
            filedata: $fp,
            metadata: $metadata
        ))();
    }


    /**
     * @see https://gist.github.com/scotthaleen/32f76a413e0dfd4b4d79c2a534d49c0b#file-tiny-jpg
     * @return string
     */
    public static function getTinyJpegData(): string
    {
        $imageDate = <<<EOT
/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA=
EOT;
        return trim($imageDate);
    }
}