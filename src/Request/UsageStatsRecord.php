<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DateTimeImmutable;
use DerSpiegel\WoodWingAssetsClient\AssetsAction;
use ReflectionClass;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042269011-Assets-Server-REST-API-Versioning-and-history
 */
class UsageStatsRecord extends Response
{
    public function __construct(
        #[MapFromJson(conversion: 'stringToAction')] readonly AssetsAction      $action = AssetsAction::Other,
        #[MapFromJson] readonly string                                          $assetDomain = '',
        #[MapFromJson] readonly string                                          $assetId = '',
        #[MapFromJson] readonly string                                          $assetPath = '',
        #[MapFromJson] readonly string                                          $assetType = '',
        #[MapFromJson] readonly array                                           $changedMetadata = [],
        #[MapFromJson] readonly string                                          $clientType = '',
        #[MapFromJson] readonly array                                           $details = [],
        #[MapFromJson] readonly string                                          $id = '',
        #[MapFromJson(conversion: 'intToDateTime')] readonly ?DateTimeImmutable $logDate = null,
        #[MapFromJson] readonly string                                          $remoteAddr = '',
        #[MapFromJson] readonly string                                          $remoteHost = '',
        #[MapFromJson] readonly string                                          $sourceAssetId = '',
        #[MapFromJson] readonly string                                          $sourceAssetPath = '',
        #[MapFromJson] readonly array                                           $userGroups = [],
        #[MapFromJson] readonly string                                          $userName = '',
        #[MapFromJson] readonly bool                                            $versionCreatingAction = false
    )
    {
    }


    public static function createFromJson(array $json): self
    {
        return (new ReflectionClass(static::class))->newInstanceArgs(self::applyJsonMapping($json));
    }
}
