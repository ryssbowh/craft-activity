<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\Categories;

class DefaultCategoryPlacement extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->fancyValue = $this->getPlacements()[$this->value] ?? $this->value;
    }

    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            Categories::CONFIG_CATEGORYROUP_KEY . '.{uid}.defaultPlacement'
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