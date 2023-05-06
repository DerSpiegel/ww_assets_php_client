<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Remove assets or collection relation
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851332-Assets-Server-REST-API-remove-relation
 */
class RemoveRelationRequest extends Request
{
    protected array $relationIds = [];


    public function execute(): ProcessResponse
    {
        try {
            $response = $this->assetsClient->serviceRequest('removeRelation',
                [
                    'relationIds' => implode(',', $this->getRelationIds())
                ]
            );
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Remove relation failed', __METHOD__), $e->getCode(), $e);
        }

        $this->logger->info('Relations removed',
            [
                'method' => __METHOD__,
                'ids' => $this->getRelationIds(),
                'response' => $response
            ]
        );

        return (new ProcessResponse())->fromJson($response);
    }


    /**
     * @return string[]
     */
    public function getRelationIds(): array
    {
        return $this->relationIds;
    }


    /**
     * @param string[] $relationIds
     * @return self
     */
    public function setRelationIds(array $relationIds): self
    {
        $this->relationIds = $relationIds;
        return $this;
    }
}
