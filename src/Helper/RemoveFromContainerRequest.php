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
    public function __construct(
        AssetsClient $assetsClient,
        public readonly string $assetId,
        public readonly string $containerId
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): ProcessResponse
    {
        $q = SearchRequest::getRelationSearchQ(
                $this->containerId,
                AssetsClient::RELATION_TARGET_CHILD,
                RelationType::Contains)
            . sprintf(' id:%s', $this->assetId);

        $searchResponse = (new SearchRequest($this->assetsClient,
            q: $q,
            num: 2,
            metadataToReturn: ['id']
        ))();

        if ($searchResponse->totalHits === 0) {
            return ProcessResponse::createFromJson(['processedCount' => 0, 'errorCount' => 0]);
        }

        $relationId = $searchResponse->hits[0]->relation['relationId'] ?? '';

        if ($relationId === '') {
            throw new AssetsException(sprintf('%s: Relation ID not found in search response', __METHOD__));
        }

        $response = (new RemoveRelationRequest($this->assetsClient, relationIds: [$relationId]))();

        $this->logger->info('Relation removed',
            [
                'method' => __METHOD__,
                'assetId' => $this->assetId,
                'containerId' => $this->containerId,
                'relationId' => $relationId
            ]
        );

        return $response;
    }
}