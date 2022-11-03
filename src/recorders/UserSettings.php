<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ProjectConfigRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class UserSettings extends ProjectConfigRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_USERS, function (Event $event) {
            Activity::getRecorder('userSettings')->onConfigChanged('users', 'userSettingsChanged', $event->oldValue, $event->newValue);
        });
    }
    
    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['allowPublicRegistration', 'defaultGroup', 'photoSubpath', 'photoVolumeUid', 'requireEmailVerification', 'suspendByDefault', 'validateOnPublicRegistration'];
    }

    /**
     * @inheritDoc
     */
    protected function _getTrackedFieldTypings(): array
    {
        return [
            'defaultGroup' => 'string'
        ];
    }
}