<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ProjectConfigRecorder;
use craft\web\Application;
use yii\base\Event;

class GeneralSettings extends ProjectConfigRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate('system', function(Event $event) {
            Activity::getRecorder('generalSettings')->onConfigChanged('system', 'generalSettingsChanged', $event->oldValue, $event->newValue);
        });
    }
    
    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['live', 'name', 'retryDuration', 'timeZone'];
    }
}