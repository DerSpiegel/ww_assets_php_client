<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Create (upload) an asset
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268771-Assets-Server-REST-API-create
 */
class CreateRequest extends CreateRequestBase
{
    public function __construct(
        AssetsClient  $assetsClient,
        mixed         $filedata = null,
        array         $metadata = [],
        array         $metadataToReturn = ['all'],
        bool          $parseMetadataModification = false,
        readonly bool $autoRename = false
    )
    {
        parent::__construct($assetsClient, $filedata, $metadata, $metadataToReturn, $parseMetadataModification);
    }


    public function __invoke(): AssetResponse
    {
        $requestData = [
            'autoRename' => $this->autoRename ? 'true' : 'false',
            'parseMetadataModifications' => $this->parseMetadataModification ? 'true' : 'false',
            'metadataToReturn' => implode(',', $this->metadataToReturn)
        ];

        $metadata = self::cleanMetadata($this->metadata);

        if (count($metadata) > 0) {
            $requestData['metadata'] = json_encode($metadata);
        }

        if (is_resource($this->filedata)) {
            $requestData['Filedata'] = $this->filedata;
        }

        try {
            $httpResponse = $this->assetsClient->serviceRequest('POST', 'create', $requestData);
        } catch (Exception $e) {
            $logData = array_filter($requestData, fn($key) => ($key !== 'Filedata'), ARRAY_FILTER_USE_KEY);

            throw new AssetsException(
                sprintf(
                    '%s: Create failed - <%s> - <%s>',
                    __METHOD__,
                    $e->getMessage(),
                    json_encode($logData)
                ),
                $e->getCode(),
                $e
            );
        }

        $assetResponse = AssetResponse::createFromHttpResponse($httpResponse);

        $this->logger->info('Asset created',
            [
                'method' => __METHOD__,
                'assetId' => $assetResponse->id->id,
                'metadata' => $metadata
            ]
        );

        return $assetResponse;
    }
}
