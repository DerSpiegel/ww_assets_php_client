<?php

namespace DerSpiegel\WoodWingElvisClient\Request;


/**
 * Class Response
 * @package DerSpiegel\WoodWingElvisClient\Request
 */
abstract class Response
{
    abstract public function fromJson(array $json): self;
}
