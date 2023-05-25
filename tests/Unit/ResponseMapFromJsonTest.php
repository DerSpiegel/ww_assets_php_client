<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Unit;

use DerSpiegel\WoodWingAssetsClient\AssetsUtils;
use DerSpiegel\WoodWingAssetsClient\Request\ProcessResponse;
use PHPUnit\Framework\TestCase;


final class ResponseMapFromJsonTest extends TestCase
{
    public function testProcessResponseJson()
    {
        $json = AssetsUtils::parseJsonResponse('{"processedCount": 13,"errorCount": 0}');

        $response = ProcessResponse::createFromJson($json);

        $this->assertEquals(13, $response->processedCount);
        $this->assertEquals(0, $response->errorCount);
    }
}
