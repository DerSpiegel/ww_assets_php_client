<?php

namespace DerSpiegel\WoodWingAssetsClient;

use ArrayIterator;
use InvalidArgumentException;


class AssetsActionList extends ArrayIterator
{
    public function __construct(AssetsAction ...$values)
    {
        parent::__construct($values);
    }


    public function current(): AssetsAction
    {
        return parent::current();
    }


    public function offsetGet(mixed $key): AssetsAction
    {
        return parent::offsetGet($key);
    }


    // It's not possible to make the $value argument type-safe, so we throw an exception instead
    public function offsetSet(mixed $key, mixed $value): void
    {
        if (!($value instanceof AssetsAction)) {
            throw new InvalidArgumentException(sprintf('%s: Only <AssetsAction> supported, got <%s>', __METHOD__, get_class($value)));
        }

        parent::offsetSet($key, $value);
    }


    // Custom, type-safe method for appending an item
    public function addValue(AssetsAction $value): void
    {
        parent::offsetSet(null, $value);
    }
}