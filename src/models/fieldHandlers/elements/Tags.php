<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementsFieldHandler;
use craft\fields\Tags as TagsField;

class Tags extends ElementsFieldHandler
{
    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            TagsField::class
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/tags-field';
    }
}