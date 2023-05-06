<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Move/rename asset or folder
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268891-Assets-Server-REST-API-move-rename
 */
class MoveRequest extends Request
{
    const FOLDER_REPLACE_POLICY_AUTO_RENAME = 'AUTO_RENAME';
    const FOLDER_REPLACE_POLICY_MERGE = 'MERGE';
    const FOLDER_REPLACE_POLICY_THROW_EXCEPTION = 'THROW_EXCEPTION';

    const FILE_REPLACE_POLICY_AUTO_RENAME = 'AUTO_RENAME';
    const FILE_REPLACE_POLICY_OVERWRITE = 'OVERWRITE';
    const FILE_REPLACE_POLICY_OVERWRITE_IF_NEWER = 'OVERWRITE_IF_NEWER';
    const FILE_REPLACE_POLICY_REMOVE_SOURCE = 'REMOVE_SOURCE';
    const FILE_REPLACE_POLICY_THROW_EXCEPTION = 'THROW_EXCEPTION';
    const FILE_REPLACE_POLICY_DO_NOTHING = 'DO_NOTHING';

    protected string $source = '';
    protected string $target = '';
    protected string $folderReplacePolicy = self::FOLDER_REPLACE_POLICY_AUTO_RENAME;
    protected string $fileReplacePolicy = self::FILE_REPLACE_POLICY_AUTO_RENAME;
    protected string $filterQuery = '';
    protected bool $flattenFolders = false;


    public function execute(): ProcessResponse
    {
        try {
            $response = $this->assetsClient->serviceRequest('move', [
                'source' => $this->getSource(),
                'target' => $this->getTarget(),
                'folderReplacePolicy' => $this->getFolderReplacePolicy(),
                'fileReplacePolicy' => $this->getFileReplacePolicy(),
                'filterQuery' => $this->getFilterQuery(),
                'flattenFolders' => $this->isFlattenFolders() ? 'true' : 'false'
            ]);
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Move/rename from <%s> to <%s> failed: %s',
                    __METHOD__,
                    $this->getSource(),
                    $this->getTarget(),
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Asset/folder moved to <%s>', $this->getTarget()),
            [
                'method' => __METHOD__,
                'source' => $this->getSource(),
                'target' => $this->getTarget(),
                'fileReplacePolicy' => $this->getFileReplacePolicy(),
                'folderReplacePolicy' => $this->getFolderReplacePolicy(),
                'filterQuery' => $this->getFilterQuery()
            ]
        );

        return (new ProcessResponse())->fromJson($response);
    }


    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }


    /**
     * @param string $source
     * @return self
     */
    public function setSource(string $source): self
    {
        $this->source = $source;
        return $this;
    }


    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }


    /**
     * @param string $target
     * @return self
     */
    public function setTarget(string $target): self
    {
        $this->target = $target;
        return $this;
    }


    /**
     * @return string
     */
    public function getFolderReplacePolicy(): string
    {
        return $this->folderReplacePolicy ?: '';
    }


    /**
     * @param string $folderReplacePolicy
     * @return self
     */
    public function setFolderReplacePolicy(string $folderReplacePolicy): self
    {
        $this->folderReplacePolicy = $folderReplacePolicy;
        return $this;
    }


    /**
     * @return string
     */
    public function getFileReplacePolicy(): string
    {
        return $this->fileReplacePolicy;
    }


    /**
     * @param string $fileReplacePolicy
     * @return self
     */
    public function setFileReplacePolicy(string $fileReplacePolicy): self
    {
        $this->fileReplacePolicy = $fileReplacePolicy;
        return $this;
    }


    /**
     * @return string
     */
    public function getFilterQuery(): string
    {
        return $this->filterQuery;
    }


    /**
     * @param string $filterQuery
     * @return self
     */
    public function setFilterQuery(string $filterQuery): self
    {
        $this->filterQuery = $filterQuery;
        return $this;
    }


    /**
     * @return bool
     */
    public function isFlattenFolders(): bool
    {
        return $this->flattenFolders;
    }


    /**
     * @param bool $flattenFolders
     * @return self
     */
    public function setFlattenFolders(bool $flattenFolders): self
    {
        $this->flattenFolders = $flattenFolders;
        return $this;
    }
}
