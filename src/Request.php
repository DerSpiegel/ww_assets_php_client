<?php

namespace DerSpiegel\WoodWingAssetsClient;

use Psr\Log\LoggerInterface;


abstract class Request
{
    protected LoggerInterface $logger;


    public function __construct(
        readonly AssetsClient $assetsClient
    )
    {
        $this->logger = $this->assetsClient->logger;
    }


    public function validate(): void
    {
    }
}
