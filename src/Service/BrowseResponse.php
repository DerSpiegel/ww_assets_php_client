<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\Response;
use Psr\Http\Message\ResponseInterface;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268711-Assets-Server-REST-API-browse
 */
class BrowseResponse extends Response
{
    public function __construct(
        readonly ?ResponseInterface $httpResponse = null,
        readonly array              $items = []
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
}
