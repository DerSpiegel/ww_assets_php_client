<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

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
        AssetsClient           $assetsClient,
        readonly ?RelationType $relationType = null,
        readonly string        $target1Id = '',
        readonly string        $target2Id = ''
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): void
    {
        try {
            $this->assetsClient->serviceRequest('createRelation',
                [
                    'relationType' => $this->relationType->value,
                    'target1Id' => $this->target1Id,
                    'target2Id' => $this->target2Id
                ]
            );
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Create relation failed', __METHOD__), $e->getCode(), $e);
        }

        $this->logger->info(
            sprintf(
                'Relation (%s) created between <%s> and <%s>',
                $this->relationType->value,
                $this->target1Id,
                $this->target2Id
            ),
            [
                'method' => __METHOD__,
                'relationType' => $this->relationType->value,
                'target1Id' => $this->target1Id,
                'target2Id' => $this->target2Id
            ]
        );
    }
}
