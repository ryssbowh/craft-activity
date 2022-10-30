<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class SectionType extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        $this->fancyValue = $this->getTypes()[$this->value] ?? $this->value;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_SECTIONS . '.{uid}.type'
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
     * get types fancy labels
     * 
     * @return array
     */
    protected function getTypes(): array
    {
        return [
            'channel' => \Craft::t('app', 'Channel'),
            'structure' => \Craft::t('app', 'Structure'),
            'single' => \Craft::t('app', 'Single')
        ];
    }
}