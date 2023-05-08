<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use Psr\Log\LoggerInterface;


abstract class Request
{
    public readonly AssetsConfig $assetsConfig;
    public readonly LoggerInterface $logger;


    public function __construct(
        public readonly AssetsClient $assetsClient
    )
    {
        $this->assetsConfig = $this->assetsClient->getConfig();
        $this->logger = $this->assetsClient->getLogger();
    }


    public function validate(): void
    {
    }
}
