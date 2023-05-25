<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DateTimeImmutable;
use ReflectionClass;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851212-Assets-Server-REST-API-checkout
 */
class CheckoutResponse extends Response
{
    public function __construct(
        #[MapFromJson(conversion: 'intToDateTime')] readonly ?DateTimeImmutable $checkedOut = null,
        #[MapFromJson] readonly string                                          $checkedOutBy = '',
        #[MapFromJson] readonly string                                          $checkedOutOnClient = ''
    )
    {
    }


    public static function createFromJson(array $json): self
    {
        return (new ReflectionClass(static::class))->newInstanceArgs(self::applyJsonMapping($json));
    }
}
