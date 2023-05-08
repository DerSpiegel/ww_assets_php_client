<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Create folder with metadata
 *
 * From the new Assets API (POST /api/folder)
 */
class CreateFolderRequest extends Request
{
    protected string $path = '';
    protected array $metadata = [];


    public function execute(): FolderResponse
    {
        try {
            $response = $this->assetsClient->apiRequest('POST', 'folder', [
                'path' => $this->getPath(),
                'metadata' => (object)$this->getMetadata()
            ]);
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Create folder failed: <%s>', __METHOD__, $e->getMessage()),
                $e->getCode(), $e);
        }

        $this->logger->info(sprintf('Folder <%s> created', $this->getPath()),
            [
                'method' => __METHOD__,
                'folderPath' => $this->getPath(),
                'metadata' => $this->getMetadata()
            ]
        );

        return (new FolderResponse())->fromJson($response);
    }


    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }


    /**
     * @param string $path
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }


    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }


    /**
     * @param array $metadata
     * @return self
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }
}
