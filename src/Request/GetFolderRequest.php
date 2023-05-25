<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


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
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): FolderResponse
    {
        try {
            $response = $this->assetsClient->apiRequest('GET', 'folder/get', [
                'path' => $this->path
            ]);
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Get folder failed: <%s>', __METHOD__, $e->getMessage()),
                $e->getCode(), $e);
        }

        $this->logger->debug(sprintf('Folder <%s> retrieved', $this->path),
            [
                'method' => __METHOD__,
                'folderPath' => $this->path
            ]
        );

        return FolderResponse::createFromJson($response);
    }
}
