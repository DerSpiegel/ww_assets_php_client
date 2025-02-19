<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;


enum HistoryDetailLevel: int
{
    case CustomActions = 0;
    case Level1 = 1;
    case Level2 = 2;
    case Level3 = 3;
    case Level4 = 4;
    case AllActions = 5;
}
