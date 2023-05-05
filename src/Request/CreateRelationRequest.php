<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\RelationType;


/**
 * Class CreateRelationRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268751-Assets-Server-REST-API-create-relation
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class CreateRelationRequest extends Request
{
    protected ?RelationType $relationType = null;
    protected string $target1Id = '';
    protected string $target2Id = '';


    /**
     * @return RelationType|null
     */
    public function getRelationType(): ?RelationType
    {
        return $this->relationType;
    }


    /**
     * @param RelationType $relationType
     * @return self
     */
    public function setRelationType(RelationType $relationType): self
    {
        $this->relationType = $relationType;
        return $this;
    }


    /**
     * @return string
     */
    public function getTarget1Id(): string
    {
        return $this->target1Id;
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
        return $this->target2Id;
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
