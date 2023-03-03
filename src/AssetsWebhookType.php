<?php

namespace DerSpiegel\WoodWingAssetsClient;

enum AssetsWebhookType: string
{
    case AssetCheckin = 'asset_checkin';
    case AssetCheckout = 'asset_checkout';
    case AssetCreate = 'asset_create';
    case AssetCreateByCopy = 'asset_create_by_copy';
    case AssetCreateFromFilestoreRescue = 'asset_create_from_filestore_rescue';
    case AssetCreateFromVersion = 'asset_create_from_version';
    case AssetMove = 'asset_move';
    case AssetPromote = 'asset_promote';
    case AssetRemove = 'asset_remove';
    case AssetRename = 'asset_rename';
    case AssetUndoCheckout = 'asset_undo_checkout';
    case AssetUpdateMetadata = 'asset_update_metadata';
    case AuthkeyCreate = 'authkey_create';
    case AuthkeyRemove = 'authkey_remove';
    case FolderCreate = 'folder_create';
    case FolderRemove = 'folder_remove';
    case Other = '';
}