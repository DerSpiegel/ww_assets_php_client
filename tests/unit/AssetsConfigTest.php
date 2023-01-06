<?php

namespace unit;

use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use PHPUnit\Framework\TestCase;


class AssetsConfigTest extends TestCase
{
    public function testGetUrl(): void
    {
        $expected = 'https://assets.example.com/';

        $config1 = new AssetsConfig('https://assets.example.com', 'u', 'p');
        $this->assertEquals($expected, $config1->getUrl());

        $config2 = new AssetsConfig('https://assets.example.com/', 'u', 'p');
        $this->assertEquals($expected, $config2->getUrl());
    }
}