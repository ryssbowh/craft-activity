<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class Sections extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_SECTIONS . '.{uid}', function (Event $event) {
            Activity::getRecorder('sections')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_SECTIONS . '.{uid}', function (Event $event) {
            Activity::getRecorder('sections')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_SECTIONS . '.{uid}', function (Event $event) {
            Activity::getRecorder('sections')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'section';
    }

    /**
     * @inheritDoc
     */
    protected function _getTrackedFieldNames(): array
    {
        return ['name', 'handle', 'type', 'enableVersioning', 'siteSettings', 'previewTargets', 'propagationMethod', 'maxLevels'];
    }

    /**
     * @inheritDoc
     */
    protected function _getTrackedFieldTypings(): array
    {
        return [
            'enableVersioning' => 'bool'
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