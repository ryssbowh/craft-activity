<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\Sections as CraftSections;
use yii\base\Event;

class Sections extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(CraftSections::CONFIG_SECTIONS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('sections')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(CraftSections::CONFIG_SECTIONS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('sections')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(CraftSections::CONFIG_SECTIONS_KEY . '.{uid}', function (Event $event) {
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
    protected function getTrackedFieldNames(): array
    {
        return ['name', 'handle', 'type', 'enableVersioning', 'siteSettings', 'previewTargets', 'propagationMethod', 'maxLevels'];
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(): array
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