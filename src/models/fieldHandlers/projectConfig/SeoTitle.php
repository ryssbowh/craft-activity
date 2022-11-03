<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;
use craft\helpers\ProjectConfig as ProjectConfigHelper;

/**
 * @since 2.2.0
 */
class SeoTitle extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if ($this->value) {
            $this->value = ProjectConfigHelper::unpackAssociativeArrays($this->value);
            $fancy = [];
            foreach ($this->value as $elem) {
                $fancy[] = $elem['locked'] ? ($elem['template'] . ' *locked*') : $elem['template'];
            }
            $this->fancyValue = implode(' ', $fancy);
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
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[ether\\seo\\fields\\SeoField].title'
        ];
    }
}