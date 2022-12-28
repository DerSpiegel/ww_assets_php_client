<?php

use DerSpiegel\WoodWingAssetsClient\AssetsUtils;
use PHPUnit\Framework\TestCase;


final class IsAssetsIdTest extends TestCase
{
    public function testValidAssetsId(): void
    {
        $this->assertEquals(true, AssetsUtils::isAssetsId('DI2cep7646g8IG_t29rBG5'));
    }


    public function testEmptyAssetsId(): void
    {
        $this->assertEquals(false, AssetsUtils::isAssetsId(''));
    }


    public function testTooShortAssetsId(): void
    {
        $this->assertEquals(false, AssetsUtils::isAssetsId('DI2cep7646g8IG_t29rBG'));
    }


    public function testTooLongAssetsId(): void
    {
        $this->assertEquals(false, AssetsUtils::isAssetsId('DI2cep7646g8IG_t29rBG55'));
    }
}
