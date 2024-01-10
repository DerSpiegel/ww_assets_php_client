<?php

namespace DerSpiegel\WoodWingAssetsClient;

use InvalidArgumentException;
use Stringable;


readonly class AssetId implements Stringable
{
    public function __construct(public string $id)
    {
        if (!self::isValid($id)) {
            throw new InvalidArgumentException('Not a valid asset ID');
        }
    }


    public function __toString(): string
    {
        return $this->id;
    }


    public static function isValid(string $id): bool
    {
        return (strlen($id) === 22);
    }
}