<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementsFieldHandler;
use craft\fields\Entries as EntriesField;

class Entries extends ElementsFieldHandler
{
    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            EntriesField::class
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/entries-field';
    }
}