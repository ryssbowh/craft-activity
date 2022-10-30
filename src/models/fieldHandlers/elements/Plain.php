<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use craft\fields\Color;
use craft\fields\Email;
use craft\fields\Number;
use craft\fields\Url;

class Plain extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            Email::class,
            Number::class,
            Url::class,
            Color::class
        ];
    }
}