<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class UndoCheckoutRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/4824964597009-Assets-Server-REST-API-promote
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class PromoteRequest extends Request
{
    protected string $id = '';
    protected int $version = 0;


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }


    /**
     * @param int $version
     * @return PromoteRequest
     */
    public function setVersion(int $version): self
    {
        $this->version = $version;
        return $this;
    }
}