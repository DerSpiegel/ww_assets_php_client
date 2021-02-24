<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsConfig;


/**
 * Class GetFolderRequest
 *
 * From the new Assets API (GET /api/folder/get)
 *
 * @package DerSpiegel\WoodWingAssetsClient\Request
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
