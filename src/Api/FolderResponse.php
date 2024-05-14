<?php

namespace DerSpiegel\WoodWingAssetsClient\Api;

use DerSpiegel\WoodWingAssetsClient\MapFromJson;
use DerSpiegel\WoodWingAssetsClient\Response;


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
}
