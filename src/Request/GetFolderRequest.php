<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Get folder metadata
 *
 * From the new Assets API (GET /api/folder/get)
 */
class GetFolderRequest extends Request
{
    protected string $path = '';


    public function execute(): FolderResponse
    {
        try {
            $response = $this->assetsClient->apiRequest('GET', 'folder/get', [
                'path' => $this->getPath()
            ]);
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Get folder failed: <%s>', __METHOD__, $e->getMessage()),
                $e->getCode(), $e);
        }

        $this->logger->debug(sprintf('Folder <%s> retrieved', $this->getPath()),
            [
                'method' => __METHOD__,
                'folderPath' => $this->getPath()
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
}
