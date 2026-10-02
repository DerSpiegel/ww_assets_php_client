<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Unit;

use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use PHPUnit\Framework\TestCase;


class AssetsConfigTest extends TestCase
{
    public function testGetUrl(): void
    {
        $config1 = AssetsConfig::create('https://a.com', 'u', 'p');
        $this->assertEquals('https://a.com', $config1->url->toRawString());

        $config2 = AssetsConfig::create('https://a.com/', 'u', 'p');
        $this->assertEquals('https://a.com/', $config2->url->toRawString());
    }


    public function testValidateUrlEmpty(): void
    {
        $this->expectExceptionMessage('URL is empty.');
        $config = AssetsConfig::create('', 'u', 'p');
        $config->validate();
    }


    public function testValidateUsernameEmpty(): void
    {
        $config = AssetsConfig::create('https://assets.example.com', '', 'p');
        $this->expectExceptionMessage('Username is empty.');
        $config->validate();
    }


    public function testValidatePasswordEmpty(): void
    {
        $config = AssetsConfig::create('https://assets.example.com', 'u', '');
        $this->expectExceptionMessage('Password is empty.');
        $config->validate();
    }
}