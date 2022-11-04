<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\Categories;
use craft\services\Sites;
use yii\base\Event;

class CategoryGroups extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(Categories::CONFIG_CATEGORYROUP_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('categoryGroups')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(Categories::CONFIG_CATEGORYROUP_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('categoryGroups')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(Categories::CONFIG_CATEGORYROUP_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('categoryGroups')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'categoryGroup';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['name', 'handle', 'structure.maxLevels', 'defaultPlacement', 'siteSettings', 'fieldLayouts'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}