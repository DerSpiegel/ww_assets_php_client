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
    const KEEP_METADATA_DEFAULT = false;


    public function __construct(
        AssetsClient $assetsClient,
        readonly ?AssetId $id = null,
        mixed $filedata = null,
        array $metadata = [],
        array $metadataToReturn = ['all'],
        bool $parseMetadataModification = false,
        readonly bool $clearCheckoutState = true,
        readonly bool $keepMetadata = self::KEEP_METADATA_DEFAULT, // Requires Assets Server 6.107 or higher
    )
    {
        parent::__construct($assetsClient, $filedata, $metadata, $metadataToReturn, $parseMetadataModification);
    }


    public function __invoke(): EmptyResponse
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

            if ($this->keepMetadata !== self::KEEP_METADATA_DEFAULT) {
                $requestData['keepMetadata'] = $this->keepMetadata ? 'true' : 'false';
            }
        }

        $httpResponse = $this->assetsClient->serviceRequest('POST', 'update', $requestData);

        $this->logger->info(
            sprintf(
                'Updated %s for asset <%s>',
                implode(', ', array_intersect(['metadata', 'Filedata'], array_keys($requestData))),
                $this->id->id
            ),
            [
                'method' => __METHOD__,
                'assetId' => $this->id->id,
                'metadata' => $this->metadata
            ]
        );

        return EmptyResponse::createFromHttpResponse($httpResponse);
    }
}
