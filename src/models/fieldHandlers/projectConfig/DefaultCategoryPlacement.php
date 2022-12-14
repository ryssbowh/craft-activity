<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class DefaultCategoryPlacement extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        $this->fancyValue = $this->getPlacements()[$this->value] ?? $this->value;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_CATEGORY_GROUPS . '.{uid}.defaultPlacement'
        ];
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }

    /**
     * Get placements fancy values
     * 
     * @return array
     */
    protected function getPlacements(): array
    {
        return [
            'beginning' => \Craft::t('app', 'Before other categories'),
            'end' => \Craft::t('app', 'After other categories')
        ];
    }
}