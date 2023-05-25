<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request;
use Exception;

/**
 * Remove Assets or Collections
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851352-Assets-Server-REST-API-remove
 */
class RemoveRequest extends Request
{
    public function __construct(
        AssetsClient    $assetsClient,
        readonly string $q = '',
        readonly array  $ids = [],
        readonly string $folderPath = ''
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): ProcessResponse
    {
        try {
            // filter the array, so the actual folder gets remove, not only its contents ?!
            $response = $this->assetsClient->serviceRequest('remove', array_filter(
                [
                    'q' => $this->q,
                    'ids' => implode(',', $this->ids),
                    'folderPath' => $this->folderPath,
                ]
            ));
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Remove failed', __METHOD__), $e->getCode(), $e);
        }

        $this->logger->info('Assets/Folders removed',
            [
                'method' => __METHOD__,
                'q' => $this->q,
                'ids' => $this->ids,
                'folderPath' => $this->folderPath,
                'response' => $response
            ]
        );

        return ProcessResponse::createFromJson($response);
    }
}
