<?php

namespace Ryssbowh\Activity\exceptions;

/**
 * @since 1.3.0
 */
class ExporterException extends \Exception
{
    public static function registered(string $handle)
    {
        return new static("Activity exporter handle '$handle' is already registered");
    }

    public static function noHandle(string $handle)
    {
        return new static("Activity exporter handle '$handle' is not defined");
    }
}