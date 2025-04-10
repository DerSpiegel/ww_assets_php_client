<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;


/**
 * Update metadata from a bunch of assets
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268991-Assets-Server-REST-API-updatebulk
 */
class UpdateBulkRequest extends CreateRequestBase
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly string $q = '',
        array $metadata = [],
        readonly bool $async = false,
        bool $parseMetadataModification = false
    ) {
        parent::__construct($assetsClient, null, $metadata, [], $parseMetadataModification);
    }


    public function __invoke(): ProcessResponse
    {
        $requestData = [
            'q' => $this->q,
            'metadata' => json_encode(self::cleanMetadata($this->metadata)),
            'parseMetadataModifications' => $this->parseMetadataModification ? 'true' : 'false',
            'async' => $this->async ? 'true' : 'false',
        ];

        $httpResponse = $this->assetsClient->serviceRequest('POST', 'updatebulk', $requestData);

        $this->logger->info(
            sprintf('Updated bulk for query <%s>', $this->q),
            [
                'method' => __METHOD__,
                'query' => $this->q,
                'metadata' => $this->metadata
            ]
        );

        return ProcessResponse::createFromHttpResponse($httpResponse);
    }
}
