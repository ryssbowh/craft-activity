<?php

namespace Ryssbowh\Activity\models\fieldHandlers\config;

use craft\models\Section;

class SectionType extends DefaultHandler
{
    public function init()
    {
        parent::init();
        $this->fancyValue = $this->getTypes()[$this->value] ?? $this->value;
    }

    public static function getTargets(): array
    {
        return [
            Section::class => 'type'
        ];
    }

    public function hasFancyValue(): bool
    {
        return true;
    }

    protected function getTypes(): array
    {
        return [
            'channel' => \Craft::t('app', 'Channel'),
            'structure' => \Craft::t('app', 'Structure'),
            'single' => \Craft::t('app', 'Single')
        ];
    }
}