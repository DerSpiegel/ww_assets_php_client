<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DateTimeImmutable;
use DerSpiegel\WoodWingAssetsClient\AssetsUtils;
use DerSpiegel\WoodWingAssetsClient\MapFromJson;
use DerSpiegel\WoodWingAssetsClient\Response;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851212-Assets-Server-REST-API-checkout
 */
class CheckoutResponse extends Response
{
    public function __construct(
        readonly ?ResponseInterface                                             $httpResponse = null,
        #[MapFromJson(conversion: 'intToDateTime')] readonly ?DateTimeImmutable $checkedOut = null,
        #[MapFromJson] readonly string                                          $checkedOutBy = '',
        #[MapFromJson] readonly string                                          $checkedOutOnClient = ''
    )
    {
    }
}
