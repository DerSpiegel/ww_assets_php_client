<?php

namespace DerSpiegel\WoodWingAssetsClient;

use Psr\Log\LoggerInterface;


abstract class Request
{
    readonly AssetsConfig $assetsConfig;
    readonly LoggerInterface $logger;


    public function __construct(
        readonly AssetsClient $assetsClient
    )
    {
        $this->assetsConfig = $this->assetsClient->config;
        $this->logger = $this->assetsClient->logger;
    }


    public function validate(): void
    {
    }
}
