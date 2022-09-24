<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\Globals;
use yii\base\Event;

class GlobalSets extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(Globals::CONFIG_GLOBALSETS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('globalSets')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(Globals::CONFIG_GLOBALSETS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('globalSets')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(Globals::CONFIG_GLOBALSETS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('globalSets')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'globalSet';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(): array
    {
        return ['name', 'handle', 'fieldLayouts'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}