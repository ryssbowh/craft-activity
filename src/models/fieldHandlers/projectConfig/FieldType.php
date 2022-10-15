<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class FieldType extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
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
            ProjectConfig::PATH_FIELDS . '.{uid}.type'
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