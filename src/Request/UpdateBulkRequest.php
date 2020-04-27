<?php

namespace DerSpiegel\WoodWingElvisClient\Request;


/**
 * Class UpdateBulkRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002690446-Elvis-6-REST-API-updatebulk
 * @package DerSpiegel\WoodWingElvisClient\Request
 */
class UpdateBulkRequest extends CreateRequest
{
    protected string $id;

    protected string $q;

    protected bool $async = false;


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id ?: '';
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
     * @return string
     */
    public function getQ(): string
    {
        return $this->q;
    }


    /**
     * @param string $q
     * @return self
     */
    public function setQ($q): self
    {
        $this->q = $q;
        return $this;
    }


    /**
     * @return bool
     */
    public function isAsync(): bool
    {
        return $this->async;
    }


    /**
     * @param bool $async
     * @return self
     */
    public function setAsync(bool $async): self
    {
        $this->async = $async;
        return $this;
    }
}
