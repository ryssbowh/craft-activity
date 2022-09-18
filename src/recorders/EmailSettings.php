<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ProjectConfigRecorder;
use yii\base\Event;

class EmailSettings extends ProjectConfigRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate('email', function(Event $event) {
            Activity::getRecorder('emailSettings')->onChanged('email', 'emailSettingsChanged', $event->oldValue, $event->newValue);
        });
    }
        
    /**
     * @inheritDoc
     */
    protected function getTrackedConfigNames(): array
    {
        return ['fromEmail', 'fromName', 'replyToEmail', 'template', 'transportType', 'transportSettings.encryptionMethod', 'transportSettings.host', 'transportSettings.password', 'transportSettings.port', 'transportSettings.timeout', 'transportSettings.useAuthentication', 'transportSettings.username', 'transportSettings.command'];
    }

    /**
     * @inheritDoc
     */
    protected function getObfuscatedSettings(): array
    {
        return ['transportSettings.password'];
    }
}