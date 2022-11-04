<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\services\Users;
use yii\base\Event;

class UserLayout extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(Users::CONFIG_USERLAYOUT_KEY, function(Event $event) {
            Activity::getRecorder('userLayout')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(Users::CONFIG_USERLAYOUT_KEY, function(Event $event) {
            Activity::getRecorder('userLayout')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(Users::CONFIG_USERLAYOUT_KEY, function(Event $event) {
            Activity::getRecorder('userLayout')->onUpdate($event);
        });
    }

    public function onUpdate(ConfigEvent $event)
    {
        $event->oldValue = ['fieldLayouts' => $event->oldValue ?? []];
        $event->newValue = ['fieldLayouts' => $event->newValue ?? []];
        $event->path = Users::CONFIG_USERS_KEY;
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
    protected function getTrackedFieldNames(array $config): array
    {
        return ['fieldLayouts'];
    }
}