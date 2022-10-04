<?php

namespace Ryssbowh\Activity\helpers;

class PrettyPrint
{
    /**
     * Get a pretty print for a value
     * 
     * @param  mixed $value
     * @return mixed
     */
    public static function get($value)
    {
        $type = gettype($value);
        if (in_array($type, ['boolean', 'array', 'NULL', 'string'])) {
            return self::$type($value);
        }
        return $value;
    }

    /**
     * Get a string pretty print
     * 
     * @param  mixed $value
     * @return string
     */
    public static function string($value): string
    {
        if ($value) {
            return $value;
        }
        return \Craft::t('activity', '*empty string*');
    }

    /**
     * Get a boolean pretty print
     * 
     * @param  mixed $value
     * @return string
     */
    public static function boolean($value): string
    {
        return $value ? 'true' : 'false';
    }

    /**
     * Get a pertty print for a null value
     * 
     * @param mixed $value
     * @return string
     */
    public static function NULL($value): string
    {
        return 'null';
    }

    /**
     * Get a pertty print for an array
     * 
     * @param mixed $value
     * @return string
     */
    public static function array($value): string
    {
        $string = '[';
        foreach ($value as $index => $val) {
            $string .= is_int($index) ? self::get($val) .', ' : "$index => " . self::get($val) . ', ';
        }
        if ($string === '[') {
            return '[]';
        }
        return substr($string, 0, -2) . ']';
    }
}