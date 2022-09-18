<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\SettingsLog;

class UserSettingsChanged extends SettingsLog
{
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Changed user settings');
    }

    public function getSettingLabels(): array
    {
        return [
            'photoSubpath' => \Craft::t('activity', 'User Photo Location subfolder'),
            'photoVolumeUid' => \Craft::t('app', 'User Photo Location'),
            'requireEmailVerification' => \Craft::t('app', 'Verify email addresses'),
            'allowPublicRegistration' => \Craft::t('app', 'Allow public registration'),
            'validateOnPublicRegistration' => \Craft::t('app', 'Validate custom fields on public registration'),
            'suspendByDefault' => \Craft::t('app', 'Suspend users by default'),
            'defaultGroup' => \Craft::t('app', 'Default User Group'),
        ];
    }
}