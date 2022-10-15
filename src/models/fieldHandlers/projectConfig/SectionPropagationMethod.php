<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class SectionPropagationMethod extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        $this->fancyValue = $this->getMethods()[$this->value] ?? $this->value;
    }

    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            ProjectConfig::PATH_SECTIONS . '.{uid}.propagationMethod'
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
     * Get methods fancy labels
     * 
     * @return array
     */
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