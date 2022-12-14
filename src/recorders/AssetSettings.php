<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ProjectConfigRecorder;
use yii\base\Event;

class AssetSettings extends ProjectConfigRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate('assets', function(Event $event) {
            Activity::getRecorder('assetSettings')->onConfigChanged('assets', 'assetSettingsChanged', $event->oldValue ?? [], $event->newValue ?? []);
        });
        \Craft::$app->projectConfig->onRemove('assets', function(Event $event) {
            Activity::getRecorder('assetSettings')->onConfigChanged('assets', 'assetSettingsChanged', $event->oldValue ?? [], []);
        });
        \Craft::$app->projectConfig->onAdd('assets', function(Event $event) {
            Activity::getRecorder('assetSettings')->onConfigChanged('assets', 'assetSettingsChanged', [], $event->newValue);
        });
    }
        
    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['tempVolumeUid', 'tempSubpath'];
    }
}