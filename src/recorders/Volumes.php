<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class Volumes extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_VOLUMES . '.{uid}', function (Event $event) {
            Activity::getRecorder('volumes')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_VOLUMES . '.{uid}', function (Event $event) {
            Activity::getRecorder('volumes')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_VOLUMES . '.{uid}', function (Event $event) {
            Activity::getRecorder('volumes')->onRemove($event);
        });
    }
    
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'volume';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(): array
    {
        return ['name', 'handle', 'fs', 'fieldLayouts', 'transformFs', 'transformSubpath'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}