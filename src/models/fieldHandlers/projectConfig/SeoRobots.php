<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;
use craft\helpers\ProjectConfig as ProjectConfigHelper;

class SeoRobots extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if ($this->value) {
            $this->value = ProjectConfigHelper::unpackAssociativeArrays($this->value);
            $this->fancyValue = implode(', ', array_filter($this->value, function ($value) {
                return $value != '';
            }));
        }
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[ether\\seo\\fields\\SeoField].robots'
        ];
    }
}