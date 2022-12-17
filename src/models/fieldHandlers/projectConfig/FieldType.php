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
        try{
            $this->fancyValue = $this->value::displayName();
        } catch (\Throwable $e) {}
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
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