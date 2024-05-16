<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\MapFromJson;
use DerSpiegel\WoodWingAssetsClient\Response;
use Psr\Http\Message\ResponseInterface;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268851-Assets-Server-REST-API-logout
 */
class LogoutResponse extends Response
{
    public function __construct(
        readonly ?ResponseInterface  $httpResponse = null,
        #[MapFromJson] readonly bool $logoutSuccess = false
    )
    {
    }
}
