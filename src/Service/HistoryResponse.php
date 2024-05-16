<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\MapFromJson;
use DerSpiegel\WoodWingAssetsClient\Response;
use Psr\Http\Message\ResponseInterface;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042269011-Assets-Server-REST-API-Versioning-and-history
 */
class HistoryResponse extends Response
{
    public function __construct(
        readonly ?ResponseInterface       $httpResponse = null,
        #[MapFromJson] readonly int       $totalHits = 0,
        readonly ?HistoryResponseItemList $hits = null
    )
    {
    }


    protected static function applyJsonMapping(array $json): array
    {
        $result = parent::applyJsonMapping($json);

        if (isset($json['hits']) && is_array($json['hits'])) {
            $result['hits'] = new HistoryResponseItemList();

            foreach ($json['hits'] as $hitJson) {
                $result['hits']->addValue(HistoryResponseItem::createFromJson($hitJson));
            }
        }

        return $result;
    }
}
