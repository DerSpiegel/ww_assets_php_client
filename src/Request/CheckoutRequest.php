<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Check out asset
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851212-Assets-Server-REST-API-checkout
 */
class CheckoutRequest extends Request
{
    protected string $id = '';
    protected bool $download = false;


    /**
     * Check out
     */
    public function execute(): CheckoutResponse
    {
        // This method is designed to do a checkout without download
        $this->setDownload(false);

        try {
            $response = $this->assetsClient->serviceRequest(
                sprintf('checkout/%s', urlencode($this->getId())),
                ['download' => $this->isDownload() ? 'true' : 'false']
            );
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Checkout of asset <%s> failed: %s',
                    __METHOD__,
                    $this->getId(),
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Asset <%s> checked out', $this->getId()),
            [
                'method' => __METHOD__,
                'id' => $this->getId(),
                'download' => $this->isDownload()
            ]
        );

        return (new CheckoutResponse())->fromJson($response);
    }


    /**
     * Check out and download
     */
    public function executeAndDownload(string $targetPath): void
    {
        // This method is designed to do a checkout with download
        $this->setDownload(true);

        try {
            $response = $this->assetsClient->rawServiceRequest(
                sprintf('checkout/%s', urlencode($this->getId())),
                ['download' => $this->isDownload() ? 'true' : 'false']
            );

            $this->assetsClient->writeResponseBodyToPath($response, $targetPath);
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Checkout of asset <%s> failed: %s',
                    __METHOD__,
                    $this->getId(),
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Asset <%s> checked out and downloaded to <%s>', $this->getId(), $targetPath),
            [
                'method' => __METHOD__,
                'id' => $this->getId(),
                'download' => $this->isDownload()
            ]
        );
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return bool
     */
    public function isDownload(): bool
    {
        return $this->download;
    }


    /**
     * @param bool $download
     * @return self
     */
    public function setDownload(bool $download): self
    {
        $this->download = $download;
        return $this;
    }
}
