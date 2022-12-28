<?php

namespace unit;

use DerSpiegel\WoodWingAssetsClient\AssetsUtils;
use PHPUnit\Framework\TestCase;


final class AssetsUtilsTest extends TestCase
{
    /**
     * @dataProvider assetsIdProvider
     */
    public function testIsAssetsId(string $assetsId, bool $expected): void
    {
        $this->assertEquals($expected, AssetsUtils::isAssetsId($assetsId));
    }


    public function assetsIdProvider(): array
    {
        return [
            'valid' => ['DI2cep7646g8IG_t29rBG5', true],
            'too short' => ['DI2cep7646g8IG_t29rBG', false],
            'too long' => ['DI2cep7646g8IG_t29rBG55', false],
            'empty' => ['', false]
        ];
    }


    public function testParseJsonResponseException(): void
    {
        $jsonString = <<<EOT
{
  "errorcode" : 401,
  "message" : "Not logged in"
}
EOT;

        $this->expectExceptionMessage('Assets error: Not logged in');
        AssetsUtils::parseJsonResponse($jsonString);
    }


    /**
     * @dataProvider elasticsearchEscapeProvider
     */
    public function testEscapeForElasticsearch(string $input, string $expected): void
    {
        $this->assertEquals($expected, AssetsUtils::escapeForElasticsearch($input));
    }


    public function elasticsearchEscapeProvider(): array
    {
        return [
            'empty' => ['', ''],
            'dos' => ['C:\\WINDOWS\\WIN.INI', 'C\\:\\\\WINDOWS\\\\WIN.INI']
        ];
    }


    public function testGetQueryTemplate(): void
    {
        $templateStr = '{% if ID %} title:"prefix {{ ID }}" {% endif %}';
        $templateVars = ['ID' => 'abc-123'];

        $template = AssetsUtils::getQueryTemplate($templateStr);

        $this->assertEquals(
            'title:"prefix abc\-123"',
            trim($template->render($templateVars))
        );
    }
}
