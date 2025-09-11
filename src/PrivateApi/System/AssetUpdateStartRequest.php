<?php

namespace DerSpiegel\WoodWingAssetsClient\PrivateApi\System;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;
use DerSpiegel\WoodWingAssetsClient\Service\ProcessResponse;


class AssetUpdateStartRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly string $filterQuery,
        readonly bool $extractMetadata = false,
        readonly bool $reEmbedMetadata = false,
        readonly bool $extractTextContent = false,
        readonly bool $deleteRemovedAssetsFromIndex = false,
        readonly bool $regenerateThumbnailsAndPreviews = false,
        readonly bool $rebuildAutoCreatedRelations = false,
        readonly bool $pruneBlacklist = false,
        readonly ?bool $runExclusive = null,
        readonly ?int $pauseMillis = null,
        readonly ?int $numThreads = null,
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): ProcessResponse
    {
        $data = $this->toArray();

        $httpResponse = $this->assetsClient->privateApiRequest(
            'POST',
            'system/asset/update/start',
            $data
        );

        $this->logger->info(
            sprintf('Sending request to <system/asset/update/start>: %s', json_encode($data)),
            [
                'method' => __METHOD__,
            ]
        );

        return ProcessResponse::createFromHttpResponse($httpResponse);
    }


    protected function toArray(): array
    {
        $params = [
            'filterQuery' => $this->filterQuery,
        ];

        $actionProperties = [
            'extractMetadata',
            'reEmbedMetadata',
            'extractTextContent',
            'deleteRemovedAssetsFromIndex',
            'regenerateThumbnailsAndPreviews',
            'rebuildAutoCreatedRelations',
            'pruneBlacklist',
        ];

        foreach ($actionProperties as $propertyName) {
            if ($this->$propertyName === true) {
                $params[$propertyName] = true;
            }
        }

        $nullableProperties = [
            'runExclusive',
            'pauseMillis',
            'numThreads',
        ];

        foreach ($nullableProperties as $propertyName) {
            if ($this->$propertyName !== null) {
                $params[$propertyName] = $this->$propertyName;
            }
        }

        return $params;
    }
}