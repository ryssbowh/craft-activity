<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\Recorder;
use craft\web\Application as WebApplication;
use yii\base\Event;

class Application extends Recorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(WebApplication::class, WebApplication::EVENT_AFTER_EDITION_CHANGE, function ($event) {
            Activity::getRecorder('application')->onEditionChanged($event->oldEdition, $event->newEdition);
        });
        \Craft::$app->projectConfig->onUpdate('system', function(Event $event) {
            Activity::getRecorder('application')->onEditionChanged($event->oldValue['edition'], $event->newValue['edition']);
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
        $this->saveLog('craftEditionChanged', [
            'changedFields' => [
                'edition' => [
                    'f' => $oldEdition,
                    't' => $newEdition
                ]
            ]
        ]);
    }
}