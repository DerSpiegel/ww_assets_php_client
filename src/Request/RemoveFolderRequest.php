<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;
use RuntimeException;


/**
 * Remove a folder
 *
 * From the new Assets API (DELETE /api/folder/{id})
 */
class RemoveFolderRequest extends Request
{
    protected string $id = '';
    protected string $path = '';


    public function execute(): void
    {
        $this->validate();

        try {
            $response = $this->assetsClient->apiRequest('DELETE', sprintf('folder/%s', $this->getId()));
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Remove failed', __METHOD__), $e->getCode(), $e);
        }

        $this->logger->info('Folder removed',
            [
                'method' => __METHOD__,
                'id' => $this->getId(),
                'folderPath' => $this->getPath(),
                'response' => $response
            ]
        );
    }


    public function validate(): void
    {
        if (trim($this->getId()) === '') {
            throw new RuntimeException(sprintf("%s: ID is empty in RemoveFolderRequest", __METHOD__));
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
}