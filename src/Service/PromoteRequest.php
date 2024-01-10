<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use BadFunctionCallException;
use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request;
use Exception;


/**
 * Promote version
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/4824964597009-Assets-Server-REST-API-promote
 */
class PromoteRequest extends Request
{
    public function __construct(
        AssetsClient      $assetsClient,
        readonly ?AssetId $id = null,
        readonly int      $version = 0
    )
    {
        parent::__construct($assetsClient);
    }


    public function validate(): void
    {
        if ($this->id === null) {
            throw new BadFunctionCallException(sprintf("%s: ID is empty in PromoteRequest", __METHOD__));
        }

        if ($this->version < 1) {
            throw new BadFunctionCallException(sprintf("%s: Version is empty in PromoteRequest", __METHOD__));
        }
    }


    public function __invoke(): void
    {
        $this->validate();

        try {
            $response = $this->assetsClient->serviceRequest(
                'version/promote',
                [
                    'assetId' => $this->id->id,
                    'version' => $this->version
                ]
            );
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Promote version <%d> of asset <%s> failed',
                    __METHOD__,
                    $this->version,
                    $this->id->id
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Promote version <%d> for asset <%s> performed', $this->version, $this->id->id),
            [
                'method' => __METHOD__,
                'id' => $this->id->id,
                'response' => $response
            ]
        );
    }
}