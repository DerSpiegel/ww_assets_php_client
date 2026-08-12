<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\MapFromJson;
use DerSpiegel\WoodWingAssetsClient\Response;
use Psr\Http\Message\ResponseInterface;


class VersionListResponse extends Response
{
    public function __construct(
        readonly ?ResponseInterface $httpResponse = null,
        readonly ?VersionResponseList $hits = null,
        #[MapFromJson] readonly int $totalHits = 0,
    ) {
    }


    protected static function applyJsonMapping(array $json): array
    {
        $result = parent::applyJsonMapping($json);

        if (isset($result['totalHits'])) {
            $result['totalHits'] = max(0, $result['totalHits']);
        }

        if (isset($json['hits']) && is_array($json['hits'])) {
            $result['hits'] = new VersionResponseList();

            foreach ($json['hits'] as $hitJson) {
                // Strange format: Older versions are wrapped in a "hit" object, the latest is not
                if (isset($hitJson['hit'])) {
                    $hitJson = $hitJson['hit'];
                }

                $result['hits']->addValue(VersionResponse::createFromJson($hitJson));
            }
        }

        return $result;
    }
}
