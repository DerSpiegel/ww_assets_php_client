<?php

namespace DerSpiegel\WoodWingAssetsClientTests\Unit;

use DerSpiegel\WoodWingAssetsClient\AssetsUtils;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;


final class AssetsUtilsTest extends TestCase
{
    public function testParseJsonResponseException(): void
    {
        $jsonString = <<<EOT
{
  "errorcode" : 401,
  "message" : "Not logged in"
}
EOT;

        $this->expectExceptionMessage('Not logged in');
        AssetsUtils::parseJsonResponse($jsonString);
    }


    #[DataProvider('elasticsearchEscapeProvider')]
    public function testEscapeForElasticsearch(string $input, string $expected): void
    {
        $this->assertEquals($expected, AssetsUtils::escapeForElasticsearch($input));
    }


    public static function elasticsearchEscapeProvider(): array
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


    #[DataProvider('cleanUpUnchangedMetadataFieldsProvider')]
    public function testCleanUpUnchangedMetadataFields(array $oldMetadata, array $newMetadata, array $expected): void
    {
        AssetsUtils::cleanUpUnchangedMetadataFields($newMetadata, $oldMetadata);

        $this->assertEquals($expected, $newMetadata);
    }


    public static function cleanUpUnchangedMetadataFieldsProvider(): array
    {
        return [
            'empty' => [
                [],
                [],
                []
            ],
            'unchanged string' => [
                ['credit' => 'dpa'],
                ['credit' => 'dpa'],
                []
            ],
            'changed string' => [
                ['credit' => 'dpa'],
                ['credit' => 'afp'],
                ['credit' => 'afp']
            ],
            'unchanged but reordered array with duplicate' => [
                ['tags' => ['a', 'b']],
                ['tags' => ['b', 'a', 'a']],
                []
            ],
            'unchanged array as scalar' => [
                ['tags' => ['a']],
                ['tags' => 'a'],
                []
            ],
            'unchanged empty array as scalar' => [
                ['tags' => []],
                ['tags' => ''],
                []
            ],
            'unchanged formatted value' => [
                ['created' => ['value' => 1521956554000, 'formatted' => '2018-03-25 07:42:34 +0200']],
                ['created' => 1521956554000],
                []
            ],
            'added value' => [
                [],
                ['headline' => 'new'],
                ['headline' => 'new']
            ],
            'added empty value' => [
                [],
                ['headline' => ''],
                []
            ],
            'removed value' => [
                ['headline' => 'old'],
                ['headline' => ''],
                ['headline' => '']
            ],
            'unspecified value' => [
                ['headline' => 'old'],
                [],
                []
            ],
        ];
    }
}
