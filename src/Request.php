<?php

namespace DerSpiegel\WoodWingAssetsClient;

use Psr\Log\LoggerInterface;


abstract class Request
{
    public readonly AssetsConfig $assetsConfig;
    public readonly LoggerInterface $logger;


    public function __construct(
        public readonly AssetsClient $assetsClient
    )
    {
        $this->assetsConfig = $this->assetsClient->config;
        $this->logger = $this->assetsClient->logger;
    }


    public function validate(): void
    {
    }
}
