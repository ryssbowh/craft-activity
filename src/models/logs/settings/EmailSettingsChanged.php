<?php

namespace Ryssbowh\Activity\models\logs\settings;

use Ryssbowh\Activity\base\logs\SettingsLog;

class EmailSettingsChanged extends SettingsLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Changed email settings');
    }

    /**
     * @inheritDoc
     */
    public function getSettingLabels(): array
    {
        return [
            'fromEmail' => \Craft::t('app', 'System Email Address'),
            'replyToEmail' => \Craft::t('app', 'Reply-To Address'),
            'fromName' => \Craft::t('app', 'Sender Name'),
            'template' => \Craft::t('app', 'HTML Email Template'),
            'transportType' => \Craft::t('app', 'Transport Type'),
            'transportSettings.encryptionMethod' => \Craft::t('app', 'Encryption Method'),
            'transportSettings.host' => \Craft::t('app', 'Hostname'),
            'transportSettings.port' => \Craft::t('app', 'Port'),
            'transportSettings.username' => \Craft::t('app', 'Username'),
            'transportSettings.useAuthentication' => \Craft::t('app', 'Use authentication'),
            'transportSettings.password' => \Craft::t('app', 'Password'),
            'transportSettings.timeout' => \Craft::t('app', 'Timeout'),
            'transportSettings.command' => \Craft::t('app', 'Sendmail Command')
        ];
    }
}