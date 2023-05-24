<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Remove assets or collection relation
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851332-Assets-Server-REST-API-remove-relation
 */
class RemoveRelationRequest extends Request
{
    public function __construct(
        AssetsClient   $assetsClient,
        readonly array $relationIds = []
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): ProcessResponse
    {
        try {
            $response = $this->assetsClient->serviceRequest('removeRelation',
                [
                    'relationIds' => implode(',', $this->relationIds)
                ]
            );
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Remove relation failed', __METHOD__), $e->getCode(), $e);
        }

        $this->logger->info('Relations removed',
            [
                'method' => __METHOD__,
                'ids' => $this->relationIds,
                'response' => $response
            ]
        );

        return (new ProcessResponse())->fromJson($response);
    }
}
