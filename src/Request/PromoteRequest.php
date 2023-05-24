<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;
use RuntimeException;


/**
 * Promote version
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/4824964597009-Assets-Server-REST-API-promote
 */
class PromoteRequest extends Request
{
    public function __construct(
        AssetsClient    $assetsClient,
        readonly string $id = '',
        readonly int    $version = 0
    )
    {
        parent::__construct($assetsClient);
    }


    public function validate(): void
    {
        if (trim($this->id) === '') {
            throw new RuntimeException(sprintf("%s: ID is empty in UndoCheckoutRequest", __METHOD__));
        }

        if ($this->version < 1) {
            throw new RuntimeException(sprintf("%s: Version is empty in UndoCheckoutRequest", __METHOD__));
        }
    }


    public function __invoke(): void
    {
        $this->validate();

        try {
            $response = $this->assetsClient->serviceRequest(
                'version/promote',
                [
                    'assetId' => $this->id,
                    'version' => $this->version
                ]
            );
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Promote version <%d> of asset <%s> failed',
                    __METHOD__,
                    $this->version,
                    $this->id
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Promote version <%d> for asset <%s> performed', $this->version, $this->id),
            [
                'method' => __METHOD__,
                'id' => $this->id,
                'response' => $response
            ]
        );
    }
}