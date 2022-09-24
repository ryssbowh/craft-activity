<?php

namespace Ryssbowh\Activity\exceptions;

class ActivityRecorderException extends \Exception
{
    public static function registered(string $handle)
    {
        return new static("Activity recorder $handle is already registered");
    }
}