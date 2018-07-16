<?php

namespace App\Utils;

abstract class AbstractEnum
{
    protected static $values = [];

    public static function getAll()
    {
        return static::$values;
    }

    /**
     * @param string $stringValue
     *
     * @return int
     */
    public static function getIntValue($stringValue)
    {
        $types = array_flip(static::getAll());
        return $types[$stringValue];
    }

    /**
     * @param int $intValue
     *
     * @return bool
     */
    public static function hasIntValue($intValue)
    {
        return isset(static::$values[$intValue]);
    }

    /**
     * @param string $stringValue
     *
     * @return bool
     */
    public static function hasStringValue($stringValue)
    {
        $intVal = static::getIntValue($stringValue);
        return static::hasIntValue($intVal);
    }

    /**
     * @param int $intValue
     *
     * @return string|null
     */
    public static function getStringValue($intValue, $nullable = false)
    {
        $types = static::getAll();
        if ($nullable && $intValue === null) {
            if (array_key_exists($intValue, $types)) {
                return $types[$intValue];
            }

            return null;
        }

        if (!isset($types[$intValue])) {
            return null;
        }

        return $types[$intValue];
    }
}
