<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\MapFromJson;
use DerSpiegel\WoodWingAssetsClient\Response;
use Psr\Http\Message\ResponseInterface;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851432-Assets-Server-REST-API-search
 */
class AssetResponse extends Response
{
    public function __construct(
        readonly ?ResponseInterface                                $httpResponse = null,
        #[MapFromJson(conversion: 'stringToId')] readonly ?AssetId $id = null,
        #[MapFromJson] readonly string                             $permissions = '',
        #[MapFromJson] readonly array                              $metadata = [],
        #[MapFromJson] readonly string                             $highlightedText = '',
        #[MapFromJson] readonly string                             $originalUrl = '',
        #[MapFromJson] readonly string                             $previewUrl = '',
        #[MapFromJson] readonly string                             $thumbnailUrl = '',
        #[MapFromJson] readonly array                              $relation = [],
        readonly ?AssetResponseList                                $thumbnailHits = null
    )
    {
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

        return $result;
    }
}
