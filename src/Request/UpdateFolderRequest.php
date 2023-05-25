<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;
use RuntimeException;


/**
 * Update folder metadata
 *
 * From the new Assets API (PUT /api/folder/{id})
 */
class UpdateFolderRequest extends Request
{
    public function __construct(
        AssetsClient    $assetsClient,
        readonly string $id = '',
        readonly string $path = '',
        readonly array  $metadata = []
    )
    {
        parent::__construct($assetsClient);
    }


    public function validate(): void
    {
        if (trim($this->id) === '') {
            throw new RuntimeException(sprintf("%s: ID is empty in UpdateFolderRequest", __METHOD__));
        }
    }


    public function __invoke(): FolderResponse
    {
        $this->validate();

        try {
            $response = $this->assetsClient->apiRequest('PUT', "folder/{$this->id}", [
                'metadata' => (object)$this->metadata
            ]);
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Update folder failed: <%s>', __METHOD__, $e->getMessage()),
                $e->getCode(), $e);
        }

        $this->logger->info(sprintf('Updated metadata for folder <%s> (%s)', $this->path, $this->id),
            [
                'method' => __METHOD__,
                'folderPath' => $this->path,
                'folderId' => $this->id
            ]
        );

        return FolderResponse::createFromJson($response);
    }
}
