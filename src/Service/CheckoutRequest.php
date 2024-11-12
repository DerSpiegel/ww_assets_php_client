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
        AssetsClient $assetsClient,
        readonly ?AssetId $id = null,
        readonly bool $download = false
    ) {
        parent::__construct($assetsClient);
    }


    /**
     * Check out (without download)
     */
    public function __invoke(): CheckoutResponse
    {
        $httpResponse = $this->assetsClient->serviceRequest(
            'POST',
            sprintf('checkout/%s', urlencode($this->id->id)),
            ['download' => 'false']
        );

        $this->logger->info(
            sprintf('Asset <%s> checked out', $this->id->id),
            [
                'method' => __METHOD__,
                'id' => $this->id->id,
                'download' => false
            ]
        );

        return CheckoutResponse::createFromHttpResponse($httpResponse);
    }


    /**
     * Check out and download
     */
    public function checkoutAndDownload(string $targetPath): EmptyResponse
    {
        $httpResponse = $this->assetsClient->serviceRequest(
            'POST',
            sprintf('checkout/%s', urlencode($this->id->id)),
            ['download' => 'true'],
            false
        );

        $this->assetsClient->writeResponseBodyToPath($httpResponse, $targetPath);

        $this->logger->info(
            sprintf('Asset <%s> checked out and downloaded to <%s>', $this->id->id, $targetPath),
            [
                'method' => __METHOD__,
                'id' => $this->id->id,
                'download' => true
            ]
        );

        return EmptyResponse::createFromHttpResponse($httpResponse);
    }
}
