<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class Response
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
abstract class Response
{
    abstract public function fromJson(array $json): self;
}
