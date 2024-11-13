<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request;
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


    public function __construct(
        AssetsClient $assetsClient,
        readonly string $source = '',
        readonly string $target = '',
        readonly string $folderReplacePolicy = self::FOLDER_REPLACE_POLICY_AUTO_RENAME,
        readonly string $fileReplacePolicy = self::FILE_REPLACE_POLICY_AUTO_RENAME,
        readonly string $filterQuery = '',
        readonly bool $flattenFolders = false
    ) {
        parent::__construct($assetsClient);
    }


    public function __invoke(): ProcessResponse
    {
        $httpResponse = $this->assetsClient->serviceRequest('POST', 'move', [
            'source' => $this->source,
            'target' => $this->target,
            'folderReplacePolicy' => $this->folderReplacePolicy,
            'fileReplacePolicy' => $this->fileReplacePolicy,
            'filterQuery' => $this->filterQuery,
            'flattenFolders' => $this->flattenFolders ? 'true' : 'false'
        ]);

        $this->logger->info(
            sprintf('Asset/folder moved to <%s>', $this->target),
            [
                'method' => __METHOD__,
                'source' => $this->source,
                'target' => $this->target,
                'fileReplacePolicy' => $this->fileReplacePolicy,
                'folderReplacePolicy' => $this->folderReplacePolicy,
                'filterQuery' => $this->filterQuery
            ]
        );

        return ProcessResponse::createFromHttpResponse($httpResponse);
    }
}
