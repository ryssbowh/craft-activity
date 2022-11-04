<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\helpers\ProjectConfig as ProjectConfigHelper;
use craft\services\ProjectConfig;

class Options extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        $value = ProjectConfigHelper::unpackAssociativeArrays($this->value);
        foreach ($value as $index => $sub) {
            $value[$index]['default'] = (bool)$sub['default'];
        }
        $this->fancyValue = $value;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\fields\\Dropdown].options',
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\fields\\Checkboxes].options',
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\fields\\MultiSelect].options',
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\fields\\RadioButtons].options',
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