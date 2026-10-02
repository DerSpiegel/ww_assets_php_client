<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\MapFromJson;
use DerSpiegel\WoodWingAssetsClient\Response;
use Psr\Http\Message\ResponseInterface;
use Uri\Rfc3986\Uri;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851432-Assets-Server-REST-API-search
 */
class AssetResponse extends Response
{
    public function __construct(
        readonly ?ResponseInterface $httpResponse = null,
        #[MapFromJson(conversion: 'stringToId')] readonly ?AssetId $id = null,
        #[MapFromJson] readonly string $permissions = '',
        #[MapFromJson] readonly array $metadata = [],
        #[MapFromJson] readonly string $highlightedText = '',
        readonly ?Uri $originalUrl = null,
        readonly ?Uri $previewUrl = null,
        readonly ?Uri $thumbnailUrl = null,
        #[MapFromJson] readonly array $relation = [],
        readonly ?AssetResponseList $thumbnailHits = null,
        #[MapFromJson] readonly string $originalStoragePath = '',
    ) {
    }


    protected static function applyJsonMapping(array $json): array
    {
        $result = parent::applyJsonMapping($json);

        if (isset($json['thumbnailHits']) && is_array($json['thumbnailHits'])) {
            $result['thumbnailHits'] = new AssetResponseList();

            foreach ($json['thumbnailHits'] as $hitJson) {
                $result['thumbnailHits']->addValue(AssetResponse::createFromJson($hitJson));
            }
        }

        foreach (['originalUrl', 'previewUrl', 'thumbnailUrl'] as $key) {
            if (!empty($json[$key])) {
                $result[$key] = new Uri($json[$key]);
            }
        }

        return $result;
    }
}
