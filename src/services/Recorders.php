<?php

namespace Ryssbowh\Activity\services;

use Ryssbowh\Activity\events\RegisterRecordersEvent;
use yii\di\ServiceLocator;

class Recorders extends ServiceLocator
{
    const EVENT_REGISTER = 'register';

    public bool $registered = false;

    public function register()
    {
        if ($this->registered) {
            return;
        }
        $event = new RegisterRecordersEvent;
        $this->trigger(self::EVENT_REGISTER, $event);
        $this->setComponents($event->recorders);
        $this->registered = true;
    }

    public function stopRecording()
    {
        foreach ($this->getComponents(false) as $recorder) {
            $recorder->stopRecording = true;
        }
    }

    public function startRecording()
    {
        foreach ($this->getComponents(false) as $recorder) {
            $recorder->stopRecording = false;
        }
    }
}