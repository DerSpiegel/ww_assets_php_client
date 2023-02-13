<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class CheckoutResponse
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851212-Assets-Server-REST-API-checkout
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class CheckoutResponse extends Response
{
    #[MapFromJson] protected int $checkedOut = 0;
    #[MapFromJson] protected string $checkedOutBy = '';
    #[MapFromJson] protected string $checkedOutOnClient = '';


    /**
     * @return int
     */
    public function getCheckedOut(): int
    {
        return $this->checkedOut;
    }


    /**
     * @return string
     */
    public function getCheckedOutBy(): string
    {
        return $this->checkedOutBy;
    }


    /**
     * @return string
     */
    public function getCheckedOutOnClient(): string
    {
        return $this->checkedOutOnClient;
    }
}
