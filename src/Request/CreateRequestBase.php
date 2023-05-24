<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;


class CreateRequestBase extends Request
{
    public function __construct(
        AssetsClient   $assetsClient,
        /** @var resource */
        readonly mixed $filedata = null,
        readonly array $metadata = [],
        readonly array $metadataToReturn = ['all'],
        readonly bool  $parseMetadataModification = false
    )
    {
        parent::__construct($assetsClient);
    }


    /**
     * For some reason, Assets fails to empty the metadata field if the sent value is an empty array
     */
    public static function cleanMetadata(array $metadata): array
    {
        foreach ($metadata as &$field) {
            if (is_array($field) && empty($field)) {
                $field = '';
            }
        }

        return $metadata;
    }
}
