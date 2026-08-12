<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\MapFromJson;
use DerSpiegel\WoodWingAssetsClient\Response;


class VersionResponse extends Response
{
    public function __construct(
        readonly ?AssetId $assetId = null,
        readonly ?int $versionNumber = null,
        #[MapFromJson] readonly string $permissionMask = '',
        #[MapFromJson] readonly array $metadata = [],
        #[MapFromJson] readonly string $originalUrl = '',
        #[MapFromJson] readonly string $previewUrl = '',
        #[MapFromJson] readonly string $thumbnailUrl = '',
    ) {
    }


    protected static function applyJsonMapping(array $json): array
    {
        $result = parent::applyJsonMapping($json);

        if (isset($json['metadata']['id'])) {
            $result['assetId'] = new AssetId($json['metadata']['id']);
        }

        if (isset($json['metadata']['versionNumber'])) {
            $result['versionNumber'] = intval($json['metadata']['versionNumber']);
        }

        return $result;
    }
}
