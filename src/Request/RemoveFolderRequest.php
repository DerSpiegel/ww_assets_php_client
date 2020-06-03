<?php

namespace DerSpiegel\WoodWingElvisClient\Request;


/**
 * Class RemoveFolderRequest
 *
 * From the new Elvis API (DELETE /api/folder/{id})
 *
 * @package DerSpiegel\WoodWingElvisClient\Request
 */
class RemoveFolderRequest extends Request
{
    protected string $id;

    protected string $path;


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
}