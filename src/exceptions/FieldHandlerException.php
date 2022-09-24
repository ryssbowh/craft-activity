<?php

namespace Ryssbowh\Activity\exceptions;

class FieldHandlerException extends \Exception
{
    public static function elementRegistered(string $class, string $handlerClass)
    {
        return new static("Field $class is already handled by class $handlerClass");
    }

    public static function projectConfigRegistered(string $path, string $handlerClass)
    {
        return new static("Project config $path is already handled by class $handlerClass");
    }
}