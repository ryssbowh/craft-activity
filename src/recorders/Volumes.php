<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\Volumes as CraftVolumes;
use yii\base\Event;

class Volumes extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(CraftVolumes::CONFIG_VOLUME_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('volumes')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(CraftVolumes::CONFIG_VOLUME_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('volumes')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(CraftVolumes::CONFIG_VOLUME_KEY . '.{uid}', function (Event $event) {
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
    protected function _getTrackedFieldNames(): array
    {
        return ['name', 'handle', 'hasUrls', 'url', 'type', 'titleTranslationMethod', 'fieldLayouts', 'titleTranslationKeyFormat', 'settings.path'];
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(): array
    {
        return [
            'hasUrls' => 'bool'
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