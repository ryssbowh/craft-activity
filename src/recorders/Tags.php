<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\Tags as CraftTags;
use yii\base\Event;

class Tags extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(CraftTags::CONFIG_TAGGROUP_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('tags')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(CraftTags::CONFIG_TAGGROUP_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('tags')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(CraftTags::CONFIG_TAGGROUP_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('tags')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'tagGroup';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
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