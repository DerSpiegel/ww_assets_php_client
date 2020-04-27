<?php

namespace DerSpiegel\WoodWingElvisClient\Request;


/**
 * Class FolderResponse
 *
 * From the new Elvis API (GET /api/folder/get)
 *
 * @package DerSpiegel\WoodWingElvisClient\Request
 */
class FolderResponse extends Response
{
    protected string $id;

    protected array $metadata;

    protected string $name;

    protected string $path;

    protected string $permissions;


    /**
     * @param array $json
     * @return self
     */
    public function fromJson(array $json): self
    {
        if (isset($json['id'])) {
            $this->id = $json['id'];
        }

        if (isset($json['name'])) {
            $this->name = $json['name'];
        }

        if (isset($json['path'])) {
            $this->path = $json['path'];
        }

        if (isset($json['permissions'])) {
            $this->permissions = $json['permissions'];
        }

        if (isset($json['metadata']) && is_array($json['metadata'])) {
            $this->metadata = $json['metadata'];
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id ?: '';
    }


    /**
     * @return string
     */
    public function getPermissions(): string
    {
        return $this->permissions ?: '';
    }


    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return (is_array($this->metadata) ? $this->metadata : []);
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?: '';
    }


    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path ?: '';
    }
}
