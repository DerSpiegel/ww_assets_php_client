<?php

namespace DerSpiegel\WoodWingAssetsClient\Api;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Get folder metadata
 *
 * From the new Assets API (GET /api/folder/get)
 */
class GetFolderRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly string $path = ''
    ) {
        parent::__construct($assetsClient);
    }


    public function __invoke(): FolderResponse
    {
        $response = $this->assetsClient->apiRequest('GET', 'folder/get', [
            'path' => $this->path
        ]);

        $this->logger->debug(
            sprintf('Folder <%s> retrieved', $this->path),
            [
                'method' => __METHOD__,
                'folderPath' => $this->path
            ]
        );

        return FolderResponse::createFromJson($response);
    }
}
