<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use Attribute;
use ReflectionProperty;


#[Attribute(Attribute::TARGET_PROPERTY)]
class MapFromJson
{
    public function __construct(
        public string $name = '',
        public ?ReflectionProperty $property = null
    )
    {
    }
}