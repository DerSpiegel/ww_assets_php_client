<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\ElvisConfig;


/**
 * Class Request
 *
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
abstract class Request
{
    protected ElvisConfig $config;


    /**
     * UpdateFolderRequest constructor.
     * @param ElvisConfig $config
     */
    public function __construct(ElvisConfig $config)
    {
        $this->config = $config;
    }
}
