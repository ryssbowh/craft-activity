<?php

namespace Ryssbowh\Activity\exceptions;

class ActivityRecorderException extends \Exception
{
    public static function registered(string $name)
    {
        return new static("Activity recorder $name is already registered");
    }
}