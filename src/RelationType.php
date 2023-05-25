<?php

namespace DerSpiegel\WoodWingAssetsClient;

enum RelationType: string
{
    case Related = 'related';
    case References = 'references';
    case ReferencedBy = 'referenced-by';
    case Contains = 'contains';
    case ContainedBy = 'contained-by';
    case Duplicate = 'duplicate';
    case Variation = 'variation';
    case VariationOf = 'variation-of';
}