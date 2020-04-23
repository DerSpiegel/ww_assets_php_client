<?php

namespace DerSpiegel\WoodWingElvisClient\Request;

use DerSpiegel\WoodWingElvisClient\ElvisConfig;


/**
 * Class GetFolderRequest
 *
 * From the new Elvis API (GET /api/folder/get)
 *
 * @package DerSpiegel\WoodWingElvisClient\Request
 */
class GetFolderRequest extends Request
{
    protected string $path;


    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path ?: '';
    }


    /**
     * @param string $path
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }
}
