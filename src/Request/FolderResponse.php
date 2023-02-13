<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class FolderResponse
 *
 * From the new Assets API (GET /api/folder/get)
 *
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class FolderResponse extends Response
{
    #[MapFromJson] protected string $id = '';
    #[MapFromJson] protected array $metadata = [];
    #[MapFromJson] protected string $name = '';
    #[MapFromJson] protected string $path = '';
    #[MapFromJson] protected string $permissions = '';


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getPermissions(): string
    {
        return $this->permissions;
    }


    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
