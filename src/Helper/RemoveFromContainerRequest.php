<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\RelationType;
use DerSpiegel\WoodWingAssetsClient\Request\ProcessResponse;
use DerSpiegel\WoodWingAssetsClient\Request\RemoveRelationRequest;
use DerSpiegel\WoodWingAssetsClient\Request\Request;
use DerSpiegel\WoodWingAssetsClient\Request\SearchRequest;


class RemoveFromContainerRequest extends Request
{
    public function execute(string $assetId, string $containerId): ProcessResponse
    {
        $q = SearchRequest::getRelationSearchQ(
                $containerId,
                AssetsClient::RELATION_TARGET_CHILD,
                RelationType::Contains)
            . sprintf(' id:%s', $assetId);

        $searchResponse = (new SearchRequest($this->assetsClient))
            ->setQ($q)
            ->setMetadataToReturn(['id'])
            ->setNum(2)
            ->execute();

        if ($searchResponse->getTotalHits() === 0) {
            return (new ProcessResponse())
                ->fromJson(['processedCount' => 0, 'errorCount' => 0]);
        }

        $relationId = $searchResponse->getHits()[0]->getRelation()['relationId'] ?? '';

        if ($relationId === '') {
            throw new AssetsException(sprintf('%s: Relation ID not found in search response', __METHOD__));
        }

        $response = (new RemoveRelationRequest($this->assetsClient))
            ->setRelationIds([$relationId])
            ->execute();

        $this->logger->info('Relation removed',
            [
                'method' => __METHOD__,
                'assetId' => $assetId,
                'containerId' => $containerId,
                'relationId' => $relationId
            ]
        );

        return $response;
    }
}