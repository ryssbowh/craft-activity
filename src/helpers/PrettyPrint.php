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

    public static function string($value)
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
    public static function boolean($value)
    {
        return $value ? 'true' : 'false';
    }

    /**
     * Get a pertty print for a null value
     * 
     * @param mixed $value
     * @return string
     */
    public static function NULL($value)
    {
        return 'null';
    }

    /**
     * Get a pertty print for an array
     * 
     * @param mixed $value
     * @return string
     */
    public static function array($value)
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