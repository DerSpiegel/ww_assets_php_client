<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Update an asset's metadata
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268971-Assets-Server-REST-API-update-check-in
 */
class UpdateRequest extends CreateRequestBase
{
    public function __construct(
        AssetsClient      $assetsClient,
        readonly ?AssetId $id = null,
        mixed             $filedata = null,
        array             $metadata = [],
        array             $metadataToReturn = ['all'],
        bool              $parseMetadataModification = false,
        readonly bool     $clearCheckoutState = true
    )
    {
        parent::__construct($assetsClient, $filedata, $metadata, $metadataToReturn, $parseMetadataModification);
    }


    public function __invoke(): void
    {
        $requestData = [
            'id' => $this->id->id,
            'parseMetadataModifications' => $this->parseMetadataModification ? 'true' : 'false',
            'metadataToReturn' => implode(',', $this->metadataToReturn)
        ];

        $metadata = self::cleanMetadata($this->metadata);

        if (count($metadata) > 0) {
            $requestData['metadata'] = json_encode($metadata);
        }

        if (is_resource($this->filedata)) {
            $requestData['Filedata'] = $this->filedata;
            $requestData['clearCheckoutState'] = $this->clearCheckoutState ? 'true' : 'false';
        }

        try {
            $this->assetsClient->serviceRequest('update', $requestData);
        } catch (Exception $e) {
            $logData = array_filter($requestData, fn($key) => ($key !== 'Filedata'), ARRAY_FILTER_USE_KEY);

            throw new AssetsException(
                sprintf(
                    '%s: Update failed for asset <%s> - <%s> - <%s>',
                    __METHOD__,
                    $this->id->id,
                    $e->getMessage(),
                    json_encode($logData)
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(
            sprintf(
                'Updated %s for asset <%s>',
                implode(array_intersect(['metadata', 'Filedata'], array_keys($requestData))),
                $this->id->id
            ),
            [
                'method' => __METHOD__,
                'assetId' => $this->id->id,
                'metadata' => $this->metadata
            ]
        );
    }
}
