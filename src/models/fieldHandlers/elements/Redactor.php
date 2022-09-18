<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\ElementFieldHandler;

class Redactor extends ElementFieldHandler
{
    public static function getTargets(): array
    {
        return [
            'craft\\redactor\\Field'
        ];
    }
}