<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class UpdateFolderRequest
 *
 * From the new Assets API (PUT /api/folder/{id})
 *
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class UpdateFolderRequest extends Request
{
    protected string $id;

    protected string $path;

    protected array $metadata;


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id ?? '';
    }


    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path ?? '';
    }


    /**
     * The path is not used by the request so it's optional, but it helps with logging if available
     *
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
