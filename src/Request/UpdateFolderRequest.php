<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

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
    protected string $id = '';
    protected string $path = '';
    protected array $metadata = [];


    public function execute(): FolderResponse
    {
        $this->validate();

        try {
            $response = $this->assetsClient->apiRequest('PUT', "folder/{$this->getId()}", [
                'metadata' => (object)$this->getMetadata()
            ]);
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Update folder failed: <%s>', __METHOD__, $e->getMessage()),
                $e->getCode(), $e);
        }

        $this->logger->info(sprintf('Updated metadata for folder <%s> (%s)', $this->getPath(), $this->getId()),
            [
                'method' => __METHOD__,
                'folderPath' => $this->getPath(),
                'folderId' => $this->getId()
            ]
        );

        return (new FolderResponse())->fromJson($response);
    }


    public function validate(): void
    {
        if (trim($this->getId()) === '') {
            throw new RuntimeException(sprintf("%s: ID is empty in UpdateFolderRequest", __METHOD__));
        }
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }


    /**
     * The path is not used by the request so it's optional, but it helps with logging if available
     *
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
