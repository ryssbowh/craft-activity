<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class CategoryGroups extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_CATEGORY_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('categoryGroups')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_CATEGORY_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('categoryGroups')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_CATEGORY_GROUPS . '.{uid}', function (Event $event) {
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
    protected function getTrackedFieldNames(): array
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