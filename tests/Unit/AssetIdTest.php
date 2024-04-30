<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Unit;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;


final class AssetIdTest extends TestCase
{
    #[DataProvider('assetIdProvider')]
    public function testAssetIdIsValid(string $assetsId, bool $expected): void
    {
        $this->assertEquals($expected, AssetId::isValid($assetsId));
    }


    public static function assetIdProvider(): array
    {
        return [
            'valid (1)' => ['DI2cep76_6g8IG_t29rBG5', true],
            'valid (2)' => ['12345677890abcdefghi-_', true],
            'invalid character: comma' => ['DI2cep76_6g,IG_t29rBG5', false],
            'invalid character: space' => ['DI2 ep76_6g8IG_t29rBG5', false],
            'too short' => ['DI2cep7646g8IG_t29rBG', false],
            'too long' => ['DI2cep7646g8IG_t29rBG55', false],
            'empty' => ['', false]
        ];
    }


    public function testNewAssetIdException(): void
    {
        $this->expectExceptionMessage('Not a valid asset ID');
        new AssetId('invalidId');
    }
}
