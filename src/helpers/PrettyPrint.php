<?php

namespace Ryssbowh\Activity\helpers;

class PrettyPrint
{
    public static function get($value)
    {
        $type = gettype($value);
        if (in_array($type, ['boolean', 'array', 'NULL'])) {
            return self::$type($value);
        }
        return $value;
    }

    public static function boolean($value)
    {
        return $value ? 'true' : 'false';
    }

    public static function NULL($value)
    {
        return 'null';
    }

    public static function array($value)
    {
        $string = '[';
        foreach ($value as $index => $val) {
            $string .= "$index => " . self::get($val) . ', ';
        }
        return substr($string, 0, -2) . ']';
    }
}