<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\Fields;

class FieldType extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->fancyValue = $this->value::displayName();
    }

    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            Fields::CONFIG_FIELDS_KEY . '.{uid}.type'
        ];
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }
}