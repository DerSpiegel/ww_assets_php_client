<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DateTimeImmutable;
use DerSpiegel\WoodWingAssetsClient\AssetsAction;
use ReflectionClass;


abstract class Response
{
    protected static function applyJsonMapping(array $json): array
    {
        $mapping = self::getJsonMapping();
        $result = [];

        foreach ($mapping as $mapFromJson) {
            $parameterName = $mapFromJson->parameter->getName();
            $parameterType = $mapFromJson->parameter->getType()->getName();
            $nameInJson = $mapFromJson->name;

            if (!isset($json[$nameInJson])) {
                continue;
            }

            $value = $json[$nameInJson];

            if (!empty($mapFromJson->conversion)) {
                if ($mapFromJson->conversion === MapFromJson::INT_TO_DATETIME) {
                    // Assets represents DateTime as Unix timestamp in milliseconds: 1675181108436
                    // Convert to 1675181108.436 and then to a DateTimeImmutable
                    $value = DateTimeImmutable::createFromFormat(
                        'U.v',
                        sprintf('%d.%03d', $value / 1000, $value % 1000)
                    );
                } elseif ($mapFromJson->conversion === MapFromJson::STRING_TO_ACTION) {
                    $value = AssetsAction::tryFrom($value) ?? AssetsAction::Other;
                }
            }

            if ($parameterType === 'array') {
                if (!is_array($value)) {
                    if (!empty($value)) {
                        $value = [$value];
                    } else {
                        continue;
                    }
                }
            } elseif ($parameterType === 'bool') {
                $value = boolval($value);
            } elseif ($parameterType === 'int') {
                $value = intval($value);
            } elseif ($parameterType === 'string') {
                $value = trim($value);
            }

            $result[$parameterName] = $value;
        }

        return $result;
    }


    /**
     * Read this object's constructor parameter MapFromJson attributes
     *
     * A simple MapFromJson attribute uses the parameter name as JSON key.
     * If the JSON key differs, specify MapFromJson(name: 'JSON key').
     * If a conversion routine is to be applied, specify it like this: MapFromJson(conversion: 'intToDateTime').
     *
     * @return MapFromJson[]
     */
    protected static function getJsonMapping(): array
    {
        $mapping = [];

        $reflection = new ReflectionClass(static::class);

        foreach ($reflection->getConstructor()->getParameters() as $parameter) {
            foreach ($parameter->getAttributes(MapFromJson::class) as $attribute) {
                $name = $attribute->getArguments()['name'] ?? $parameter->getName();
                $conversion = $attribute->getArguments()['conversion'] ?? null;
                $mapping[] = new MapFromJson($name, $parameter, $conversion);
                break;
            }
        }

        return $mapping;
    }
}
