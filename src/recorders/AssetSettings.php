<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ProjectConfigRecorder;
use yii\base\Event;

class AssetSettings extends ProjectConfigRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate('assets', function(Event $event) {
            Activity::getRecorder('assetSettings')->onChanged('assets', 'assetSettingsChanged', $event->oldValue ?? [], $event->newValue ?? []);
        });
        \Craft::$app->projectConfig->onRemove('assets', function(Event $event) {
            Activity::getRecorder('assetSettings')->onChanged('assets', 'assetSettingsChanged', $event->oldValue ?? [], []);
        });
        \Craft::$app->projectConfig->onAdd('assets', function(Event $event) {
            Activity::getRecorder('assetSettings')->onChanged('assets', 'assetSettingsChanged', [], $event->newValue);
        });
    }
        
    /**
     * @inheritDoc
     */
    protected function getTrackedConfigNames(): array
    {
        return ['tempVolumeUid', 'tempSubpath'];
    }
}