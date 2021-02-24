<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsConfig;


/**
 * Class Request
 *
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
abstract class Request
{
    protected AssetsConfig $config;


    /**
     * UpdateFolderRequest constructor.
     * @param AssetsConfig $config
     */
    public function __construct(AssetsConfig $config)
    {
        $this->config = $config;
    }
}
