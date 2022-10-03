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

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/redactor-field';
    }
}