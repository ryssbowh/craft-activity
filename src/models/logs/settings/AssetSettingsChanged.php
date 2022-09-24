<?php

namespace Ryssbowh\Activity\models\logs\settings;

use Ryssbowh\Activity\base\logs\SettingsLog;

class AssetSettingsChanged extends SettingsLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Changed asset settings');
    }

    /**
     * @inheritDoc
     */
    public function getSettingLabels(): array
    {
        return [
            'tempSubpath' => \Craft::t('activity', 'Temp Uploads Location Subpath'),
            'tempVolumeUid' => \Craft::t('app', 'Temp Uploads Location')
        ];
    }
}