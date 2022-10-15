<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\services\ProjectConfig;
use yii\base\Event;

class AddressLayout extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_ADDRESSES, function(Event $event) {
            Activity::getRecorder('addressLayout')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_ADDRESSES, function(Event $event) {
            Activity::getRecorder('addressLayout')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_ADDRESSES, function(Event $event) {
            Activity::getRecorder('addressLayout')->onUpdate($event);
        });
    }

    public function onUpdate(ConfigEvent $event)
    {
        $event->path = ProjectConfig::PATH_ADDRESSES;
        parent::onUpdate($event);
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'addressLayout';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(): array
    {
        return ['fieldLayouts'];
    }
}