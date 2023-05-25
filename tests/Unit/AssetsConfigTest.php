<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Unit;

use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use PHPUnit\Framework\TestCase;


class AssetsConfigTest extends TestCase
{
    public function testGetUrl(): void
    {
        $expected = 'https://a.com/';

        $config1 = AssetsConfig::create('https://a.com', 'u', 'p');
        $this->assertEquals($expected, $config1->url);

        $config2 = AssetsConfig::create('https://a.com/', 'u', 'p');
        $this->assertEquals($expected, $config2->url);
    }


    public function testValidateUrlEmpty(): void
    {
        $config = AssetsConfig::create('', 'u', 'p');
        $this->expectExceptionMessage('URL is empty.');
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