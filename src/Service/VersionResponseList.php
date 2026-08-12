<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use ArrayIterator;
use InvalidArgumentException;


class VersionResponseList extends ArrayIterator
{
    public function __construct(VersionResponse ...$values)
    {
        parent::__construct($values);
    }


    public function current(): VersionResponse
    {
        return parent::current();
    }


    public function offsetGet(mixed $key): VersionResponse
    {
        return parent::offsetGet($key);
    }


    // It's not possible to make the $value argument type-safe, so we throw an exception instead
    public function offsetSet(mixed $key, mixed $value): void
    {
        if (!($value instanceof VersionResponse)) {
            throw new InvalidArgumentException(sprintf('%s: Only <VersionResponse> supported, got <%s>', __METHOD__, get_class($value)));
        }

        parent::offsetSet($key, $value);
    }


    // Custom, type-safe method for appending an item
    public function addValue(VersionResponse $value): void
    {
        parent::offsetSet(null, $value);
    }


    public function keysByVersionNumber(): array
    {
        $result = [];

        foreach ($this as $key => $value) {
            $result[$value->versionNumber] = $key;
        }

        ksort($result, SORT_NUMERIC);

        return $result;
    }


    public function getFirstVersion(): ?VersionResponse
    {
        $keysByVersionNumber = $this->keysByVersionNumber();

        if (count($keysByVersionNumber) === 0) {
            return null;
        }

        return $this[array_first($keysByVersionNumber)];
    }


    public function getLatestVersion(): ?VersionResponse
    {
        $keysByVersionNumber = $this->keysByVersionNumber();

        if (count($keysByVersionNumber) === 0) {
            return null;
        }

        return $this[array_last($keysByVersionNumber)];
    }
}