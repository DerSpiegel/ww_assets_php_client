<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class RemoveRelationRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851332-Assets-Server-REST-API-remove-relation
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class RemoveRelationRequest extends Request
{
    protected array $relationIds = [];


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
