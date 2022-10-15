<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ProjectConfigRecorder;
use yii\base\Event;

class UserSettings extends ProjectConfigRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate('users', function(Event $event) {
            Activity::getRecorder('userSettings')->onChanged('users', 'userSettingsChanged', $event->oldValue, $event->newValue);
        });
    }
    
    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(): array
    {
        return ['allowPublicRegistration', 'defaultGroup', 'photoSubpath', 'photoVolumeUid', 'requireEmailVerification', 'suspendByDefault', 'validateOnPublicRegistration'];
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(): array
    {
        return [
            'defaultGroup' => 'string'
        ];
    }
}