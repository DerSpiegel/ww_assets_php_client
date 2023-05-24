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
    public function __invoke(): AssetResponse
    {
        $data = self::cleanMetadata($this->metadata);

        if (is_resource($this->filedata)) {
            $data['Filedata'] = $this->filedata;
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
                'metadata' => $this->metadata
            ]
        );

        return $assetResponse;
    }
}
