<?php

namespace DerSpiegel\WoodWingElvisClient\Request;


/**
 * Class RemoveRelationRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002690326-Elvis-6-REST-API-remove-relation
 * @package DerSpiegel\WoodWingElvisClient\Request
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
