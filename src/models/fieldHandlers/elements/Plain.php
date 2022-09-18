<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\ElementFieldHandler;
use craft\fields\Color;
use craft\fields\Email;
use craft\fields\Lightswitch;
use craft\fields\Number;
use craft\fields\PlainText;
use craft\fields\Url;

class Plain extends ElementFieldHandler
{
    public static function getTargets(): array
    {
        return [
            Email::class,
            Lightswitch::class,
            Number::class,
            PlainText::class,
            Url::class,
            Color::class
        ];
    }
}