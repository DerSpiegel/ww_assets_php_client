<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class CheckoutRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851212-Assets-Server-REST-API-checkout
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class CheckoutRequest extends Request
{
    protected string $id;

    protected bool $download = false;


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
    public function isDownload(): bool
    {
        return $this->download;
    }


    /**
     * @param bool $download
     * @return self
     */
    public function setDownload(bool $download): self
    {
        $this->download = $download;
        return $this;
    }
}
