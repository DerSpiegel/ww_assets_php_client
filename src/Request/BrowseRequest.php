<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268711-Assets-Server-REST-API-browse
 */
class BrowseRequest extends Request
{
    const INCLUDE_FOLDERS_DEFAULT = true;


    public function __construct(
        AssetsClient $assetsClient,
        readonly string $path = '',
        readonly string $fromRoot = '',
        readonly bool $includeFolders = self::INCLUDE_FOLDERS_DEFAULT,
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): BrowseResponse
    {
        try {
            $response = $this->assetsClient->serviceRequest('browse', $this->toArray());
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Browse failed: <%s>', __METHOD__, $e->getMessage()), $e->getCode(),
                $e);
        }

        $this->logger->debug('Browse performed',
            [
                'method' => __METHOD__,
                'path' => $this->path
            ]
        );

        return BrowseResponse::createFromJson($response);
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
