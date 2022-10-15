<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class Fields extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_FIELDS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fields')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_FIELDS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fields')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_FIELDS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fields')->onRemove($event);
        });
    }
        
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'field';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(): array
    {
        return ['name', 'type', 'instructions', 'handle', 'fieldGroup', 'instructions', 'searchable', 'translationMethod', 'translationKeyFormat'];
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(): array
    {
        return [
            'searchable' => 'bool'
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