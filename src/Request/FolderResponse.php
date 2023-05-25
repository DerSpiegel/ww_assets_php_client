<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use ReflectionClass;


/**
 * From the new Assets API (GET /api/folder/get)
 */
class FolderResponse extends Response
{
    public function __construct(
        #[MapFromJson] readonly string $id = '',
        #[MapFromJson] readonly array  $metadata = [],
        #[MapFromJson] readonly string $name = '',
        #[MapFromJson] readonly string $path = '',
        #[MapFromJson] readonly string $permissions = ''
    )
    {
    }


    public static function createFromJson(array $json): self
    {
        return (new ReflectionClass(static::class))->newInstanceArgs(self::applyJsonMapping($json));
    }
}
