<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class AssetResponse
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851432-Assets-Server-REST-API-search
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class AssetResponse extends Response
{
    #[MapFromJson] protected string $id = '';
    #[MapFromJson] protected string $permissions = '';
    #[MapFromJson] protected array $metadata = [];
    #[MapFromJson] protected string $highlightedText = '';
    #[MapFromJson] protected string $originalUrl = '';
    #[MapFromJson] protected string $previewUrl = '';
    #[MapFromJson] protected string $thumbnailUrl = '';
    #[MapFromJson] protected array $relation = [];


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
    public function getHighlightedText(): string
    {
        return $this->highlightedText;
    }


    /**
     * @return string
     */
    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }


    /**
     * @return string
     */
    public function getPreviewUrl(): string
    {
        return $this->previewUrl;
    }


    /**
     * @return string
     */
    public function getThumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }


    /**
     * @return array
     */
    public function getRelation(): array
    {
        return $this->relation;
    }
}
