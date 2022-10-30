<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class TitleTranslationMethod extends DefaultHandler
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
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_VOLUMES . '.{uid}.titleTranslationMethod',
            ProjectConfig::PATH_ENTRY_TYPES . '.{uid}.titleTranslationMethod',
            ProjectConfig::PATH_FIELDS . '.{uid}.translationMethod'
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
     * Get methods fancy labels]
     * 
     * @return array
     */
    protected function getMethods(): array
    {
        return [
            'none' => \Craft::t('app', 'Not translatable'),
            'site' => \Craft::t('app', 'Translate for each site'),
            'siteGroup' => \Craft::t('app', 'Translate for each site group'),
            'language' => \Craft::t('app', 'Translate for each language'),
            'custom' => \Craft::t('app', 'Custom')
        ];
    }
}