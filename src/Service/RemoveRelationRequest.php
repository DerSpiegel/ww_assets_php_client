<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Remove assets or collection relation
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851332-Assets-Server-REST-API-remove-relation
 */
class RemoveRelationRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly array $relationIds = []
    ) {
        parent::__construct($assetsClient);
    }


    public function __invoke(): ProcessResponse
    {
        $httpResponse = $this->assetsClient->serviceRequest(
            'POST', 'removeRelation',
            [
                'relationIds' => implode(',', $this->relationIds)
            ]
        );

        $this->logger->info(
            'Relations removed',
            [
                'method' => __METHOD__,
                'ids' => $this->relationIds,
                'response' => $httpResponse
            ]
        );

        return ProcessResponse::createFromHttpResponse($httpResponse);
    }
}
