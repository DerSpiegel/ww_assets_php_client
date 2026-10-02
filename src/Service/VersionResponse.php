<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\MapFromJson;
use DerSpiegel\WoodWingAssetsClient\Response;
use Uri\Rfc3986\Uri;


class VersionResponse extends Response
{
    public function __construct(
        readonly ?AssetId $assetId = null,
        readonly ?int $versionNumber = null,
        #[MapFromJson] readonly string $permissionMask = '',
        #[MapFromJson] readonly array $metadata = [],
        readonly ?Uri $originalUrl = null,
        readonly ?Uri $previewUrl = null,
        readonly ?Uri $thumbnailUrl = null,
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

        foreach (['originalUrl', 'previewUrl', 'thumbnailUrl'] as $key) {
            if (!empty($json[$key])) {
                $result[$key] = new Uri($json[$key]);
            }
        }

        return $result;
    }
}
