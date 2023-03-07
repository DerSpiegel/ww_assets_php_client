<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use Attribute;
use ReflectionProperty;


#[Attribute(Attribute::TARGET_PROPERTY)]
class MapFromJson
{
    public const INT_TO_DATETIME = 'intToDateTime';
    public const STRING_TO_ACTION = 'stringToAction';


    public function __construct(
        public string $name = '',
        public ?ReflectionProperty $property = null,
        public ?string $conversion = null
    )
    {
    }
}