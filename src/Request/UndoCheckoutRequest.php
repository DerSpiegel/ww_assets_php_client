<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class UndoCheckoutRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268951-Assets-REST-API-undo-checkout
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class UndoCheckoutRequest extends Request
{
    protected string $id = '';


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
}