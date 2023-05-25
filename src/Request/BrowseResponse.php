<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use ReflectionClass;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268711-Assets-Server-REST-API-browse
 */
class BrowseResponse extends Response
{
    public function __construct(
        readonly array $items = []
    )
    {
    }


    protected static function applyJsonMapping(array $json): array
    {
        $result = ['items' => []];

        foreach ($json as $item) {
            if (is_array($item)) {
                $result['items'][] = $item;
            }
        }

        return $result;
    }


    public static function createFromJson(array $json): self
    {
        return (new ReflectionClass(static::class))->newInstanceArgs(self::applyJsonMapping($json));
    }
}
