<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Copy asset
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268731-Assets-Server-REST-API-copy
 */
class CopyRequest extends Request
{
    const FILE_REPLACE_POLICY_AUTO_RENAME = 'AUTO_RENAME';
    const FILE_REPLACE_POLICY_OVERWRITE = 'OVERWRITE';
    const FILE_REPLACE_POLICY_OVERWRITE_IF_NEWER = 'OVERWRITE_IF_NEWER';
    const FILE_REPLACE_POLICY_REMOVE_SOURCE = 'REMOVE_SOURCE';
    const FILE_REPLACE_POLICY_THROW_EXCEPTION = 'THROW_EXCEPTION';
    const FILE_REPLACE_POLICY_DO_NOTHING = 'DO_NOTHING';


    public function __construct(
        AssetsClient    $assetsClient,
        readonly string $source = '',
        readonly string $target = '',
        readonly string $fileReplacePolicy = self::FILE_REPLACE_POLICY_AUTO_RENAME

    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): ProcessResponse
    {
        try {
            $response = $this->assetsClient->serviceRequest('copy', [
                'source' => $this->source,
                'target' => $this->target,
                'fileReplacePolicy' => $this->fileReplacePolicy
            ]);
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Copy from <%s> to <%s> failed: %s',
                    __METHOD__,
                    $this->source,
                    $this->target,
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Asset copied to <%s>', $this->target),
            [
                'method' => __METHOD__,
                'source' => $this->source,
                'target' => $this->target,
                'fileReplacePolicy' => $this->fileReplacePolicy
            ]
        );

        return (new ProcessResponse())->fromJson($response);
    }
}
