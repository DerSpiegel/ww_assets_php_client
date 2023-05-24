<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Create folder with metadata
 *
 * From the new Assets API (POST /api/folder)
 */
class CreateFolderRequest extends Request
{
    public function __construct(
        AssetsClient    $assetsClient,
        readonly string $path = '',
        readonly array  $metadata = []
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): FolderResponse
    {
        try {
            $response = $this->assetsClient->apiRequest('POST', 'folder', [
                'path' => $this->path,
                'metadata' => (object)$this->metadata
            ]);
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Create folder failed: <%s>', __METHOD__, $e->getMessage()),
                $e->getCode(), $e);
        }

        $this->logger->info(sprintf('Folder <%s> created', $this->path),
            [
                'method' => __METHOD__,
                'folderPath' => $this->path,
                'metadata' => $this->metadata
            ]
        );

        return (new FolderResponse())->fromJson($response);
    }
}
