<?php

namespace Ryssbowh\Activity\models\logs\settings;

use Ryssbowh\Activity\base\SettingsLog;

class AssetSettingsChanged extends SettingsLog
{
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Changed asset settings');
    }

    public function getSettingLabels(): array
    {
        return [
            'tempSubpath' => \Craft::t('activity', 'Temp Uploads Location Subpath'),
            'tempVolumeUid' => \Craft::t('app', 'Temp Uploads Location')
        ];
    }
}