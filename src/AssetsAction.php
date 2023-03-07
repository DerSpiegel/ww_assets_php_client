<?php

namespace DerSpiegel\WoodWingAssetsClient;

enum AssetsAction: string
{
    case Checkin = 'CHECKIN';
    case Checkout = 'CHECKOUT';
    case Copy = 'COPY';
    case CopyVersion = 'COPY_VERSION';
    case Create = 'CREATE';
    case CreateVariation = 'CREATE_VARIATION';
    case Download = 'DOWNLOAD';
    case MetadataUpdate = 'METADATA_UPDATE';
    case Move = 'MOVE';
    case Preview = 'PREVIEW';
    case Remove = 'REMOVE';
    case RemoveVersion = 'REMOVE_VERSION';
    case Rename = 'RENAME';
    case Rendition = 'RENDITION';
    case UndoCheckout = 'UNDO_CHECKOUT';
    case Other = '';
}