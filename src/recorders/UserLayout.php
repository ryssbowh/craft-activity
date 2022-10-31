<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\services\ProjectConfig;
use yii\base\Event;

class UserLayout extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_USER_FIELD_LAYOUTS, function(Event $event) {
            Activity::getRecorder('userLayout')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_USER_FIELD_LAYOUTS, function(Event $event) {
            Activity::getRecorder('userLayout')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_USER_FIELD_LAYOUTS, function(Event $event) {
            Activity::getRecorder('userLayout')->onUpdate($event);
        });
    }

    public function onUpdate(ConfigEvent $event)
    {
        $event->oldValue = ['fieldLayouts' => $event->oldValue ?? []];
        $event->newValue = ['fieldLayouts' => $event->newValue ?? []];
        $event->path = ProjectConfig::PATH_USERS;
        parent::onUpdate($event);
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'userLayout';
    }

    /**
     * @inheritDoc
     */
    protected function _getTrackedFieldNames(): array
    {
        return ['fieldLayouts'];
    }
}