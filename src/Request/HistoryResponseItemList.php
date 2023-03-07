<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use ArrayIterator;
use InvalidArgumentException;


class HistoryResponseItemList extends ArrayIterator
{
    public function __construct(HistoryResponseItem ...$values)
    {
        parent::__construct($values);
    }


    public function current(): HistoryResponseItem
    {
        return parent::current();
    }


    public function offsetGet(mixed $key): HistoryResponseItem
    {
        return parent::offsetGet($key);
    }


    // It's not possible to make the $value argument type-safe, so we throw an exception instead
    public function offsetSet(mixed $key, mixed $value): void
    {
        if (!($value instanceof HistoryResponseItem)) {
            throw new InvalidArgumentException(sprintf('%s: Only <HistoryResponseItem> supported, got <%s>', __METHOD__, get_class($value)));
        }

        parent::offsetSet($key, $value);
    }


    // Custom, type-safe method for appending an item
    public function addValue(HistoryResponseItem $value): void
    {
        parent::offsetSet(null, $value);
    }

}