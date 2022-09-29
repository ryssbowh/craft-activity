<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use craft\fields\Lightswitch as LightswitchField;

class Lightswitch extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            LightswitchField::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/lightswitch';
    }
}