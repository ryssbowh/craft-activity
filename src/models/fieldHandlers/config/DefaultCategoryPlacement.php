<?php

namespace Ryssbowh\Activity\models\fieldHandlers\config;

use craft\models\CategoryGroup;

class DefaultCategoryPlacement extends DefaultHandler
{
    public function init()
    {
        parent::init();
        $this->fancyValue = $this->getPlacements()[$this->value] ?? $this->value;
    }

    public static function getTargets(): array
    {
        return [
            CategoryGroup::class => 'defaultPlacement'
        ];
    }

    public function hasFancyValue(): bool
    {
        return true;
    }

    protected function getPlacements(): array
    {
        return [
            'beginning' => \Craft::t('app', 'Before other categories'),
            'end' => \Craft::t('app', 'After other categories')
        ];
    }
}