<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use craft\fields\Color;
use craft\fields\Email;
use craft\fields\Number;
use craft\fields\PlainText;
use craft\fields\Url;

class Plain extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            Email::class,
            Number::class,
            PlainText::class,
            Url::class,
            Color::class
        ];
    }
}