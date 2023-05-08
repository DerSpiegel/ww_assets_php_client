<?php

namespace DerSpiegel\WoodWingAssetsClient\Helper;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request\AssetResponse;
use DerSpiegel\WoodWingAssetsClient\Request\Request;
use RuntimeException;


class DownloadOriginalFileRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        public readonly string $targetPath,
        public readonly ?string $assetId = null,
        public readonly ?AssetResponse $assetResponse = null
    )
    {
        parent::__construct($assetsClient);
    }


    public function validate(): void
    {
        if ($this->assetResponse === null) {
            if ($this->assetId === null) {
                throw new RuntimeException(sprintf("%s: Both assetId and assetResponse are null - one of them must be given", __METHOD__));
            } elseif (trim($this->assetId) === '') {
                throw new RuntimeException(sprintf("%s: assetId is empty", __METHOD__));
            }
        }
    }


    public function execute(): void
    {
        if ($this->assetResponse !== null) {
            $assetResponse = $this->assetResponse;
        } else {
            $assetResponse = (new SearchAssetRequest($this->assetsClient, assetId: $this->assetId))->execute();
        }

        if (strlen($assetResponse->getOriginalUrl()) === 0) {
            throw new AssetsException(sprintf('%s: Original URL of <%s> is empty', __METHOD__, $assetResponse->getId()), 404);
        }

        $this->assetsClient->downloadFileToPath($assetResponse->getOriginalUrl(), $this->targetPath);

        $this->logger->debug(sprintf('Original file of <%s> downloaded to <%s>', $assetResponse->getId(), $this->targetPath),
            [
                'method' => __METHOD__,
                'assetId' => $assetResponse->getId()
            ]
        );
    }
}