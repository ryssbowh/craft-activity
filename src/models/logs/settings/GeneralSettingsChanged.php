<?php

namespace Ryssbowh\Activity\models\logs\settings;

use Ryssbowh\Activity\base\SettingsLog;

class GeneralSettingsChanged extends SettingsLog
{
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Changed general settings');
    }

    public function getSettingLabels(): array
    {
        return [
            'name' => \Craft::t('app', 'System Name'),
            'live' => \Craft::t('app', 'System Status'),
            'edition' => \Craft::t('app', 'Edition'),
            'retryDuration' => \Craft::t('app', 'Retry Duration'),
            'timeZone' => \Craft::t('app', 'Time Zone'),
            'schemaVersion' => \Craft::t('activity', 'Schema version'),
        ];
    }
}