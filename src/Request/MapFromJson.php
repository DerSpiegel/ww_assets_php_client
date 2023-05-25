<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use Attribute;
use ReflectionParameter;


#[Attribute(Attribute::TARGET_PROPERTY)]
class MapFromJson
{
    public const INT_TO_DATETIME = 'intToDateTime';
    public const STRING_TO_ACTION = 'stringToAction';


    public function __construct(
        public string               $name = '', // Key in JSON
        public ?ReflectionParameter $parameter = null,
        public ?string              $conversion = null
    )
    {
    }
}