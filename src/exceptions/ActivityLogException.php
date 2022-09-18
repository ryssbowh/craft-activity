<?php

namespace Ryssbowh\Activity\exceptions;

class ActivityLogException extends \Exception
{
    public static function noType()
    {
        return new static('Activity log is missing a "type" parameter in order to be saved');
    }
}