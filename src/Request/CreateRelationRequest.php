<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\ElvisConfig;


/**
 * Class CreateRelationRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002663363-Elvis-6-REST-API-create-relation
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class CreateRelationRequest extends Request
{
    const RELATION_TYPE_RELATED = 'related';
    const RELATION_TYPE_REFERENCES = 'references';
    const RELATION_TYPE_REFERENCED_BY = 'referenced-by';
    const RELATION_TYPE_CONTAINS = 'contains';
    const RELATION_TYPE_CONTAINED_BY = 'contained-by';
    const RELATION_TYPE_DUPLICATE = 'duplicate';
    const RELATION_TYPE_VARIATION = 'variation';
    const RELATION_TYPE_VARIATION_OF = 'variation-of';

    protected string $relationType;

    protected string $target1Id;

    protected string $target2Id;


    /**
     * @return string
     */
    public function getRelationType(): string
    {
        return $this->relationType ?: '';
    }


    /**
     * @param string $relationType
     * @return self
     */
    public function setRelationType(string $relationType): self
    {
        $this->relationType = $relationType;
        return $this;
    }


    /**
     * @return string
     */
    public function getTarget1Id(): string
    {
        return $this->target1Id ?: '';
    }


    /**
     * @param string $target1Id
     * @return self
     */
    public function setTarget1Id(string $target1Id): self
    {
        $this->target1Id = $target1Id;
        return $this;
    }


    /**
     * @return string
     */
    public function getTarget2Id(): string
    {
        return $this->target2Id ?: '';
    }


    /**
     * @param string $target2Id
     * @return self
     */
    public function setTarget2Id(string $target2Id): self
    {
        $this->target2Id = $target2Id;
        return $this;
    }
}
