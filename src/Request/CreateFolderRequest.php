<?php


namespace DerSpiegel\WoodWingElvisClient\Request;

use DerSpiegel\WoodWingElvisClient\ElvisConfig;


/**
 * Class CreateFolderRequest
 *
 * From the new Elvis API (POST /api/folder)
 *
 * @package DerSpiegel\WoodWingElvisClient\Request
 */
class CreateFolderRequest extends Request
{
    protected string $path;

    protected array $metadata;


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


    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return (is_array($this->metadata) ? $this->metadata : []);
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
