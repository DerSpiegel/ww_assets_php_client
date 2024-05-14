<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\Response;
use Psr\Http\Message\ResponseInterface;


class EmptyResponse extends Response
{
    public function __construct(
        readonly ?ResponseInterface $httpResponse = null
    )
    {
    }
}
