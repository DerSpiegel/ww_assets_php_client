<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use Psr\Log\LoggerInterface;


abstract class Request
{
    protected AssetsConfig $assetsConfig;
    protected LoggerInterface $logger;


    public function __construct(
        protected AssetsClient $assetsClient
    )
    {
        $this->assetsConfig = $this->assetsClient->getConfig();
        $this->logger = $this->assetsClient->getLogger();
    }
}
