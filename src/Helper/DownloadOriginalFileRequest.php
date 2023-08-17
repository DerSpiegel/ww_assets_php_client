<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use BadFunctionCallException;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request;
use DerSpiegel\WoodWingAssetsClient\Service\AssetResponse;


class DownloadOriginalFileRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly string $targetPath,
        readonly ?string $assetId = null,
        readonly ?AssetResponse $assetResponse = null
    )
    {
        parent::__construct($assetsClient);
    }


    public function validate(): void
    {
        if ($this->assetResponse === null) {
            if ($this->assetId === null) {
                throw new BadFunctionCallException(sprintf("%s: Both assetId and assetResponse are null - one of them must be given", __METHOD__));
            } elseif (trim($this->assetId) === '') {
                throw new BadFunctionCallException(sprintf("%s: assetId is empty", __METHOD__));
            }
        }
    }


    public function __invoke(): void
    {
        if ($this->assetResponse !== null) {
            $assetResponse = $this->assetResponse;
        } else {
            $assetResponse = (new SearchAssetRequest($this->assetsClient, assetId: $this->assetId))();
        }

        if (strlen($assetResponse->originalUrl) === 0) {
            throw new AssetsException(sprintf('%s: Original URL of <%s> is empty', __METHOD__, $assetResponse->id), 404);
        }

        $this->assetsClient->downloadFileToPath($assetResponse->originalUrl, $this->targetPath);

        $this->logger->debug(sprintf('Original file of <%s> downloaded to <%s>', $assetResponse->id, $this->targetPath),
            [
                'method' => __METHOD__,
                'assetId' => $assetResponse->id
            ]
        );
    }
}