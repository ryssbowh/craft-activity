<?php

namespace Ryssbowh\Activity\exceptions;

class ActivityTypeException extends \Exception
{
    public static function noHandle(string $handle)
    {
        return new static("Activity type $handle is not registered");
    }

    public static function registered(string $handle, string $class)
    {
        return new static("Activity type handle $handle is already registered by class $class");
    }
}