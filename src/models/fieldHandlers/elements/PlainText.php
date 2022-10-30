<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use craft\fields\PlainText as PlainTextField;

class PlainText extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            PlainTextField::class
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/plaintext-field';
    }
}