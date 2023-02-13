<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


use ReflectionClass;

/**
 * Class Response
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
abstract class Response
{
    /**
     * @param array $json
     * @return self
     */
    public function fromJson(array $json): self
    {
        foreach ($this->getPropertyFromJsonMapping() as $mapFromJson) {
            $propertyName = $mapFromJson->property->getName();
            $propertyType = $mapFromJson->property->getType()->getName();
            $nameInJson = $mapFromJson->name;

            if (!isset($json[$nameInJson])) {
                continue;
            }

            $value = $json[$nameInJson];

            if ($propertyType === 'array') {
                if (!is_array($value)) {
                    continue;
                }
            } elseif ($propertyType === 'bool') {
                $value = boolval($value);
            } elseif ($propertyType === 'int') {
                $value = intval($value);
            } elseif ($propertyType === 'string') {
                $value = trim($value);
            }

            $this->{$propertyName} = $value;
        }

        return $this;
    }


    /**
     * Read this object's MapFromJson attributes
     *
     * A simple MapFromJson attribute uses the property name as JSON key.
     * If the JSON key differs, specify MapFromJson(name: 'JSON key').
     *
     * @return MapFromJson[]
     */
    protected function getPropertyFromJsonMapping(): array
    {
        $mapping = [];

        $reflection = new ReflectionClass(static::class);

        foreach ($reflection->getProperties() as $property) {
            foreach ($property->getAttributes(MapFromJson::class) as $attribute) {
                $name = $attribute->getArguments()['name'] ?? $property->getName();
                $mapping[] = new MapFromJson($name, $property);
                break;
            }
        }

        return $mapping;
    }
}
