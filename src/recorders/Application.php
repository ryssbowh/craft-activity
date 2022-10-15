<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\Recorder;
use craft\web\Application as WebApplication;
use yii\base\Event;

class Application extends Recorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate('system.edition', function(Event $event) {
            Activity::getRecorder('application')->onEditionChanged($event->oldValue, $event->newValue);
        });
    }
    
    /**
     * Record log when the edition is changed
     * 
     * @param string $oldEdition
     * @param string $newEdition
     */
    public function onEditionChanged(string $oldEdition, string $newEdition)
    {
        if (!$this->shouldSaveLog('craftEditionChanged') or $oldEdition == $newEdition) {
            return;
        }
        $this->commitLog('craftEditionChanged', [
            'changedFields' => [
                'edition' => [
                    'data' => [
                        'f' => $oldEdition,
                        't' => $newEdition
                    ]
                ]
            ]
        ]);
    }
}