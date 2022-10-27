<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;

class LongText extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            'craft\\redactor\\Field',
            'spicyweb\\tinymce\\fields\\TinyMCE'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/longtext-field';
    }
}