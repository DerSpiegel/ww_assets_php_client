<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request;
use Exception;


/**
 * Check out asset
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851212-Assets-Server-REST-API-checkout
 */
class CheckoutRequest extends Request
{
    public function __construct(
        AssetsClient      $assetsClient,
        readonly ?AssetId $id = null,
        readonly bool     $download = false
    )
    {
        parent::__construct($assetsClient);
    }


    /**
     * Check out (without download)
     */
    public function __invoke(): CheckoutResponse
    {
        try {
            $response = $this->assetsClient->serviceRequest(
                'POST',
                sprintf('checkout/%s', urlencode($this->id->id)),
                ['download' => 'false']
            );
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Checkout of asset <%s> failed: %s',
                    __METHOD__,
                    $this->id,
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Asset <%s> checked out', $this->id->id),
            [
                'method' => __METHOD__,
                'id' => $this->id->id,
                'download' => false
            ]
        );

        return CheckoutResponse::createFromJson($response);
    }


    /**
     * Check out and download
     */
    public function checkoutAndDownload(string $targetPath): void
    {
        try {
            $response = $this->assetsClient->rawServiceRequest(
                'POST',
                sprintf('checkout/%s', urlencode($this->id->id)),
                ['download' => 'true'],
                false
            );

            $this->assetsClient->writeResponseBodyToPath($response, $targetPath);
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Checkout of asset <%s> failed: %s',
                    __METHOD__,
                    $this->id->id,
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Asset <%s> checked out and downloaded to <%s>', $this->id->id, $targetPath),
            [
                'method' => __METHOD__,
                'id' => $this->id->id,
                'download' => true
            ]
        );
    }
}
