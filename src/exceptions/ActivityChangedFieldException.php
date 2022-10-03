<?php

namespace Ryssbowh\Activity\exceptions;

class ActivityChangedFieldException extends \Exception
{
    public static function noId($id)
    {
        return new static("Activity changed field with id $id could not be found");
    }
}