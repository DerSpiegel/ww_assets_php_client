<?php

namespace DerSpiegel\WoodWingElvisClient\Request;

use DerSpiegel\WoodWingElvisClient\ElvisConfig;


/**
 * Class Request
 *
 * @package DerSpiegel\WoodWingElvisClient\Request
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
