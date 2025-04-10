<?php

namespace DerSpiegel\WoodWingAssetsClient\Api;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Create folder with metadata
 *
 * From the new Assets API (POST /api/folder)
 */
class CreateFolderRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly string $path = '',
        /** @var array<string, mixed> */
        readonly array $metadata = []
    ) {
        parent::__construct($assetsClient);
    }


    public function __invoke(): FolderResponse
    {
        $response = $this->assetsClient->apiRequest('POST', 'folder', [
            'path' => $this->path,
            'metadata' => (object)$this->metadata
        ]);

        $this->logger->info(
            sprintf('Folder <%s> created', $this->path),
            [
                'method' => __METHOD__,
                'folderPath' => $this->path,
                'metadata' => $this->metadata
            ]
        );

        return FolderResponse::createFromJson($response);
    }
}
