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

        $response = (new ProcessResponse())->fromJson($json);

        $this->assertEquals(13, $response->getProcessedCount());
        $this->assertEquals(0, $response->getErrorCount());
    }
}
