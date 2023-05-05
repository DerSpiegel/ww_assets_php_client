<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;

/**
 * Remove Assets or Collections
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851352-Assets-Server-REST-API-remove
 */
class RemoveRequest extends Request
{
    protected string $q = '';
    protected array $ids = [];
    protected string $folderPath = '';


    public function execute(): ProcessResponse
    {
        try {
            // filter the array, so the actual folder gets remove, not only its contents ?!
            $response = $this->assetsClient->serviceRequest('remove', array_filter(
                [
                    'q' => $this->getQ(),
                    'ids' => implode(',', $this->getIds()),
                    'folderPath' => $this->getFolderPath(),
                ]
            ));
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Remove failed', __METHOD__), $e->getCode(), $e);
        }

        $this->logger->info('Assets/Folders removed',
            [
                'method' => __METHOD__,
                'q' => $this->getQ(),
                'ids' => $this->getIds(),
                'folderPath' => $this->getFolderPath(),
                'response' => $response
            ]
        );

        return (new ProcessResponse())->fromJson($response);
    }


    /**
     * @return string
     */
    public function getQ(): string
    {
        return $this->q;
    }


    /**
     * @param string $q
     * @return self
     */
    public function setQ(string $q): self
    {
        $this->q = $q;
        return $this;
    }


    /**
     * @return string[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }


    /**
     * @param string[] $ids
     * @return self
     */
    public function setIds(array $ids): self
    {
        $this->ids = $ids;
        return $this;
    }


    /**
     * @return string
     */
    public function getFolderPath(): string
    {
        return $this->folderPath;
    }


    /**
     * @param string $folderPath
     * @return self
     */
    public function setFolderPath(string $folderPath): self
    {
        $this->folderPath = $folderPath;
        return $this;
    }
}
