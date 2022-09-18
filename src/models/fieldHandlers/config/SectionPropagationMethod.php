<?php

namespace Ryssbowh\Activity\models\fieldHandlers\config;

use craft\models\Section;

class SectionPropagationMethod extends DefaultHandler
{
    public function init()
    {
        parent::init();
        $this->fancyValue = $this->getMethods()[$this->value] ?? $this->value;
    }

    public static function getTargets(): array
    {
        return [
            Section::class => 'propagationMethod'
        ];
    }

    public function hasFancyValue(): bool
    {
        return true;
    }

    protected function getMethods(): array
    {
        return [
            'none' => \Craft::t('app', 'Only save entries to the site they were created in'),
            'siteGroup' => \Craft::t('app', 'Save entries to other sites in the same site group'),
            'language' => \Craft::t('app', 'Save entries to other sites with the same language'),
            'all' => \Craft::t('app', 'Save entries to all sites enabled for this section'),
            'custom' => \Craft::t('app', 'Let each entry choose which sites it should be saved to')
        ];
    }
}