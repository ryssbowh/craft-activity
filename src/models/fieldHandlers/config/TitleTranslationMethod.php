<?php

namespace Ryssbowh\Activity\models\fieldHandlers\config;

class TitleTranslationMethod extends DefaultHandler
{
    public function init()
    {
        parent::init();
        $this->fancyValue = $this->getMethods()[$this->value] ?? $this->value;
    }

    public static function getTargets(): array
    {
        $targets = [];
        foreach (\Craft::$app->volumes->getAllVolumeTypes() as $class) {
            $targets[$class] = 'titleTranslationMethod';
        }
        return $targets;
    }

    public function hasFancyValue(): bool
    {
        return true;
    }

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