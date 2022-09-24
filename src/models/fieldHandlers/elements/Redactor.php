<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;

class Redactor extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            'craft\\redactor\\Field'
        ];
    }
}