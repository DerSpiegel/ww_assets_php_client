<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class UpdateRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268971-Assets-Server-REST-API-update-check-in
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class UpdateRequest extends CreateRequest
{
    /** @var resource */
    protected $filedata;

    protected string $id = '';
    protected bool $clearCheckoutState = true;


    /**
     * @return resource
     */
    public function getFiledata()
    {
        return $this->filedata;
    }


    /**
     * @param resource $fp
     * @return self
     */
    public function setFiledata($fp): self
    {
        $this->filedata = $fp;
        return $this;
    }


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
