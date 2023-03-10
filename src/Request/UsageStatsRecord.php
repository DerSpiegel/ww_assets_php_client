<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DateTimeImmutable;
use DerSpiegel\WoodWingAssetsClient\AssetsAction;


/**
 * Class UsageStatsRecord
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042269011-Assets-Server-REST-API-Versioning-and-history
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class UsageStatsRecord extends Response
{
    #[MapFromJson(conversion: 'stringToAction')] protected AssetsAction $action = AssetsAction::Other;
    #[MapFromJson] protected string $assetDomain = '';
    #[MapFromJson] protected string $assetId = '';
    #[MapFromJson] protected string $assetPath = '';
    #[MapFromJson] protected string $assetType = '';
    #[MapFromJson] protected array $changedMetadata = [];
    #[MapFromJson] protected string $clientType = '';
    #[MapFromJson] protected array $details = [];
    #[MapFromJson] protected string $id = '';
    #[MapFromJson(conversion: 'intToDateTime')] protected ?DateTimeImmutable $logDate = null;
    #[MapFromJson] protected string $remoteAddr = '';
    #[MapFromJson] protected string $remoteHost = '';
    #[MapFromJson] protected string $sourceAssetId = '';
    #[MapFromJson] protected string $sourceAssetPath = '';
    #[MapFromJson] protected array $userGroups = [];
    #[MapFromJson] protected string $userName = '';
    #[MapFromJson] protected bool $versionCreatingAction = false;


    /**
     * @return string
     */
    public function getAction(): AssetsAction
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getAssetDomain(): string
    {
        return $this->assetDomain;
    }

    /**
     * @return string
     */
    public function getAssetId(): string
    {
        return $this->assetId;
    }

    /**
     * @return string
     */
    public function getAssetPath(): string
    {
        return $this->assetPath;
    }

    /**
     * @return string
     */
    public function getAssetType(): string
    {
        return $this->assetType;
    }

    /**
     * @return array
     */
    public function getChangedMetadata(): array
    {
        return $this->changedMetadata;
    }

    /**
     * @return string
     */
    public function getClientType(): string
    {
        return $this->clientType;
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getLogDate(): ?DateTimeImmutable
    {
        return $this->logDate;
    }

    /**
     * @return string
     */
    public function getRemoteAddr(): string
    {
        return $this->remoteAddr;
    }

    /**
     * @return string
     */
    public function getRemoteHost(): string
    {
        return $this->remoteHost;
    }

    /**
     * @return string
     */
    public function getSourceAssetId(): string
    {
        return $this->sourceAssetId;
    }

    /**
     * @return string
     */
    public function getSourceAssetPath(): string
    {
        return $this->sourceAssetPath;
    }

    /**
     * @return array
     */
    public function getUserGroups(): array
    {
        return $this->userGroups;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return bool
     */
    public function isVersionCreatingAction(): bool
    {
        return $this->versionCreatingAction;
    }
}
