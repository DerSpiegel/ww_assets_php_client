<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Unit;

use DerSpiegel\WoodWingAssetsClient\AssetsUtils;
use DerSpiegel\WoodWingAssetsClient\Service\AssetResponse;
use DerSpiegel\WoodWingAssetsClient\Service\ProcessResponse;
use PHPUnit\Framework\TestCase;


final class ResponseMapFromJsonTest extends TestCase
{
    public function testAssetResponseJson()
    {
        $json = AssetsUtils::parseJsonResponse(<<<'EOD'
          {
            "permissions" : "VPUM-----",
            "id" : "DHWrgjWCqg0ByPIdY5yM0G",
            "metadata" : {
              "widthPt" : 1281.6000000000001,
              "folderPath" : "/Demo Zone/Images/Travel/Landmarks",
              "creatorCity" : "Montreal",
              "xmpMetadataDate" : {
                "value" : 1332505018000,
                "formatted" : "2012-03-23 13:16:58 +0100"
              },
              "creatorName" : "Nicolas Raymond, Nicolas Raymond",
              "heightMm" : 301.41333333333336,
              "colorSpace" : "1",
              "filename" : "Singing Sands Beach.jpg",
              "rating" : 3,
              "thumbnailState" : "yes"
            },
            "originalUrl" : "http://demo.assets-server.com/file/DHWrgjWCqg0ByPIdY5yM0G/*/Singing%20Sands%20Beach.jpg?_=5",
            "previewUrl" : "http://demo.assets-server.com/preview/DHWrgjWCqg0ByPIdY5yM0G/previews/maxWidth_1600_maxHeight_1600.jpg/*/Singing%2520Sands%2520Beach_preview.jpg?_=1",
            "thumbnailUrl" : "http://demo.assets-server.com/thumbnail/DHWrgjWCqg0ByPIdY5yM0G/*/Singing%20Sands%20Beach_thumb.jpg?_=1"
          }
        EOD
        );

        $response = AssetResponse::createFromJson($json);

        $this->assertEquals('DHWrgjWCqg0ByPIdY5yM0G', $response->id->id);
    }


    public function testProcessResponseJson()
    {
        $json = AssetsUtils::parseJsonResponse('{"processedCount": 13,"errorCount": 0}');

        $response = ProcessResponse::createFromJson($json);

        $this->assertEquals(13, $response->processedCount);
        $this->assertEquals(0, $response->errorCount);
    }
}
