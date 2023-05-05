<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Create (upload) an asset
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268771-Assets-Server-REST-API-create
 */
class CreateRequest extends CreateRequestBase
{
    public function execute(): AssetResponse
    {
        $data = $this->getMetadata();

        $fp = $this->getFiledata();

        if (is_resource($fp)) {
            $data['Filedata'] = $fp;
        }

        try {
            $response = $this->assetsClient->serviceRequest('create', $data);
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Create failed: %s', __METHOD__, $e->getMessage()), $e->getCode(), $e);
        }

        $assetResponse = (new AssetResponse())->fromJson($response);

        $this->logger->info('Asset created',
            [
                'method' => __METHOD__,
                'metadata' => $this->getMetadata()
            ]
        );

        return $assetResponse;
    }
}
