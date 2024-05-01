<?php

namespace Ryssbowh\Activity\services;

use Ryssbowh\Activity\base\recorders\Recorder;
use Ryssbowh\Activity\events\RegisterRecordersEvent;
use yii\di\ServiceLocator;

class Recorders extends ServiceLocator
{
    public const EVENT_REGISTER = 'register';

    /**
     * @var boolean
     */
    public bool $registered = false;

    /**
     * Register recorders
     */
    public function register()
    {
        if ($this->registered) {
            return;
        }
        $event = new RegisterRecordersEvent();
        $this->trigger(self::EVENT_REGISTER, $event);
        $this->setComponents($event->recorders);
        $this->registered = true;
    }

    /**
     * get all recorders to save their committed logs
     */
    public function saveLogs()
    {
        foreach ($this->getComponents(false) as $recorder) {
            $recorder->saveLogs();
        }
    }

    /**
     * Get all recorders to stop recording
     */
    public function stopRecording()
    {
        foreach ($this->getComponents(false) as $recorder) {
            $recorder->stopRecording();
        }
    }

    /**
     * Get all recorders to start recording
     */
    public function startRecording()
    {
        foreach ($this->getComponents(false) as $recorder) {
            $recorder->startRecording();
        }
    }

    /**
     * Empty queues of all recorders
     */
    public function emptyQueues()
    {
        foreach ($this->getComponents(false) as $recorder) {
            $recorder->emptyQueue();
        }
    }
}
