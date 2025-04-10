<?php

namespace DerSpiegel\WoodWingAssetsClient\Api;

use BadFunctionCallException;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Remove a folder
 *
 * From the new Assets API (DELETE /api/folder/{id})
 */
class RemoveFolderRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly string $id = '',
        readonly string $path = ''
    ) {
        parent::__construct($assetsClient);
    }


    public function validate(): void
    {
        if (trim($this->id) === '') {
            throw new BadFunctionCallException(sprintf("%s: ID is empty in RemoveFolderRequest", __METHOD__));
        }
    }


    public function __invoke(): void
    {
        $this->validate();

        $response = $this->assetsClient->apiRequest('DELETE', sprintf('folder/%s', $this->id));

        $this->logger->info(
            'Folder removed',
            [
                'method' => __METHOD__,
                'id' => $this->id,
                'folderPath' => $this->path,
                'response' => $response
            ]
        );
    }
}