<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

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
        AssetsClient $assetsClient,
        readonly string $id = '',
        mixed $filedata = null,
        array $metadata = [],
        array $metadataToReturn = ['all'],
        bool $parseMetadataModification = false,
        readonly bool $clearCheckoutState = true
)
    {
        parent::__construct($assetsClient, $filedata, $metadata, $metadataToReturn, $parseMetadataModification);
    }


    public function __invoke(): void
    {
        $requestData = [
            'id' => $this->id,
            'parseMetadataModifications' => $this->parseMetadataModification ? 'true' : 'false'
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
            throw new AssetsException(
                sprintf(
                    '%s: Update failed for asset <%s> - <%s> - <%s>',
                    __METHOD__,
                    $this->id,
                    $e->getMessage(),
                    json_encode($requestData)
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(
            sprintf(
                'Updated %s for asset <%s>',
                implode(array_intersect(['metadata', 'Filedata'], array_keys($requestData))),
                $this->id
            ),
            [
                'method' => __METHOD__,
                'assetId' => $this->id,
                'metadata' => $this->metadata
            ]
        );
    }
}
