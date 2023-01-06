<?php

namespace unit;

use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use PHPUnit\Framework\TestCase;


class AssetsConfigTest extends TestCase
{
    public function testGetUrl(): void
    {
        $expected = 'https://a.com/';

        $config1 = new AssetsConfig('https://a.com', 'u', 'p');
        $this->assertEquals($expected, $config1->getUrl());

        $config2 = new AssetsConfig('https://a.com/', 'u', 'p');
        $this->assertEquals($expected, $config2->getUrl());
    }


    public function testValidateUrlEmpty(): void
    {
        $config = new AssetsConfig('', 'u', 'p');
        $this->expectExceptionMessage('URL is empty.');
        $config->validate();
    }


    public function testValidateUsernameEmpty(): void
    {
        $config = new AssetsConfig('https://assets.example.com', '', 'p');
        $this->expectExceptionMessage('Username is empty.');
        $config->validate();
    }


    public function testValidatePasswordEmpty(): void
    {
        $config = new AssetsConfig('https://assets.example.com', 'u', '');
        $this->expectExceptionMessage('Password is empty.');
        $config->validate();
    }
}