<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\helpers\ProjectConfig as ProjectConfigHelper;
use craft\services\Fields;

/**
 * @since 1.2.0
 */
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
            Fields::CONFIG_FIELDS_KEY . '.{uid}.settings[ether\\seo\\fields\\SeoField].robots'
        ];
    }
}