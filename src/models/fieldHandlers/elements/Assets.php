<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementsFieldHandler;
use craft\fields\Assets as AssetsField;

class Assets extends ElementsFieldHandler
{
    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            AssetsField::class
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/assets-field';
    }
}