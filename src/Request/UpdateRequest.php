<?php

namespace DerSpiegel\WoodWingElvisClient\Request;


/**
 * Class UpdateRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002690426-Elvis-6-REST-API-update-check-in
 * @package DerSpiegel\WoodWingElvisClient\Request
 */
class UpdateRequest extends CreateRequest
{
    protected string $id;

    protected bool $clearCheckoutState = true;


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
     * @return bool
     */
    public function isClearCheckoutState(): bool
    {
        return $this->clearCheckoutState;
    }


    /**
     * @param bool $clearCheckoutState
     * @return self
     */
    public function setClearCheckoutState(bool $clearCheckoutState): self
    {
        $this->clearCheckoutState = $clearCheckoutState;
        return $this;
    }
}
