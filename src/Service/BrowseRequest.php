<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268711-Assets-Server-REST-API-browse
 */
class BrowseRequest extends Request
{
    const bool INCLUDE_FOLDERS_DEFAULT = true;


    public function __construct(
        AssetsClient $assetsClient,
        readonly string $path = '',
        readonly string $fromRoot = '',
        readonly bool $includeFolders = self::INCLUDE_FOLDERS_DEFAULT,
    ) {
        parent::__construct($assetsClient);
    }


    public function __invoke(): BrowseResponse
    {
        $httpResponse = $this->assetsClient->serviceRequest('POST', 'browse', $this->toArray());

        $this->logger->debug(
            'Browse performed',
            [
                'method' => __METHOD__,
                'path' => $this->path
            ]
        );

        return BrowseResponse::createFromHttpResponse($httpResponse);
    }


    protected function toArray(): array
    {
        $params = [
            'path' => $this->path
        ];

        if (!empty($this->fromRoot)) {
            $params['fromRoot'] = $this->fromRoot;
        }

        if ($this->includeFolders !== self::INCLUDE_FOLDERS_DEFAULT) {
            $params['includeFolders'] = ($this->includeFolders ? 'true' : 'false');
        }

        return $params;
    }
}
