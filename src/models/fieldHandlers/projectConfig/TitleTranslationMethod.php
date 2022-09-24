<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\Fields;
use craft\services\Sections;
use craft\services\Volumes;

class TitleTranslationMethod extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init()
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
            Volumes::CONFIG_VOLUME_KEY . '.{uid}.titleTranslationMethod',
            Sections::CONFIG_ENTRYTYPES_KEY . '.{uid}.titleTranslationMethod',
            Fields::CONFIG_FIELDS_KEY . '.{uid}.translationMethod'
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