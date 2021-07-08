<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class CreateFolderRequest
 *
 * From the new Assets API (POST /api/folder)
 *
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class CreateFolderRequest extends Request
{
    protected string $path = '';
    protected array $metadata = [];


    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
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


    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }


    /**
     * @param array $metadata
     * @return self
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }
}
