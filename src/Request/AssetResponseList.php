<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use ArrayIterator;
use InvalidArgumentException;


class AssetResponseList extends ArrayIterator
{
    public function __construct(AssetResponse ...$values)
    {
        parent::__construct($values);
    }


    public function current(): AssetResponse
    {
        return parent::current();
    }


    public function offsetGet(mixed $key): AssetResponse
    {
        return parent::offsetGet($key);
    }


    // It's not possible to make the $value argument type-safe, so we throw an exception instead
    public function offsetSet(mixed $key, mixed $value): void
    {
        if (!($value instanceof AssetResponse)) {
            throw new InvalidArgumentException(sprintf('%s: Only <AssetResponse> supported, got <%s>', __METHOD__, get_class($value)));
        }

        parent::offsetSet($key, $value);
    }


    // Custom, type-safe method for appending an item
    public function addValue(AssetResponse $value): void
    {
        parent::offsetSet(null, $value);
    }
}