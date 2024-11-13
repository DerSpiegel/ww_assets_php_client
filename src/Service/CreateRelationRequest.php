<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\RelationType;
use DerSpiegel\WoodWingAssetsClient\Request;
use Exception;


/**
 * Create a relation between two assets
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268751-Assets-Server-REST-API-create-relation
 */
class CreateRelationRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly ?RelationType $relationType = null,
        readonly ?AssetId $target1Id = null,
        readonly ?AssetId $target2Id = null
    ) {
        parent::__construct($assetsClient);
    }


    public function __invoke(): EmptyResponse
    {
        $httpResponse = $this->assetsClient->serviceRequest(
            'POST', 'createRelation',
            [
                'relationType' => $this->relationType->value,
                'target1Id' => $this->target1Id->id,
                'target2Id' => $this->target2Id->id
            ]
        );

        $this->logger->info(
            sprintf(
                'Relation (%s) created between <%s> and <%s>',
                $this->relationType->value,
                $this->target1Id->id,
                $this->target2Id->id
            ),
            [
                'method' => __METHOD__,
                'relationType' => $this->relationType->value,
                'target1Id' => $this->target1Id->id,
                'target2Id' => $this->target2Id->id
            ]
        );

        return EmptyResponse::createFromHttpResponse($httpResponse);
    }
}
