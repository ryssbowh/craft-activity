<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class Sites extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_SITES . '.{uid}', function (Event $event) {
            Activity::getRecorder('sites')->onUpdate($event);
            Activity::getRecorder('categoryGroups')->emptyQueue();
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_SITES . '.{uid}', function (Event $event) {
            Activity::getRecorder('sites')->onAdd($event);
            Activity::getRecorder('categoryGroups')->emptyQueue();
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_SITES . '.{uid}', function (Event $event) {
            Activity::getRecorder('sites')->onRemove($event);
            Activity::getRecorder('categoryGroups')->emptyQueue();
        });
    }
    
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'site';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(): array
    {
        return ['name', 'handle', 'language', 'primary', 'hasUrls', 'baseUrl', 'enabled', 'siteGroup'];
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(): array
    {
        return [
            'hasUrls' => 'bool',
            'enabled' => 'bool',
            'primary' => 'bool'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}