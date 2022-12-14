<?php

namespace Ryssbowh\Activity\base\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\logs\ActivityLog;
use Ryssbowh\Activity\exceptions\ActivityTypeException;
use Ryssbowh\Activity\services\Logs;
use craft\base\Component;

abstract class Recorder extends Component
{
    /**
     * @var boolean
     */
    protected bool $_isRecording = true;


    /**
     * @var array
     */
    protected $logQueue = [];

    /**
     * Save all queued logs
     */
    public function saveLogs()
    {
        foreach ($this->logQueue as $log) {
            $log->save();
        }
        $this->emptyQueue();
    }

    /**
     * Does a log related to an uid and type exists in the queue
     * 
     * @param  string $uid
     * @param  string $type
     * @return bool
     */
    public function queueHasLogForUid(string $uid, string $type): bool
    {
        return $this->getQueuedLogByUid($uid, $type) !== null;
    }

    /**
     * Get a queued log for an uid and type
     * 
     * @param  string $uid
     * @param  string $type
     * @return ?ActivityLog
     */
    public function getQueuedLogByUid(string $uid, string $type): ?ActivityLog
    {
        $logs = array_filter($this->logQueue, function ($log) use ($uid, $type) {
            return ($uid == $log->target_uid and $log->handle == $type);
        });
        return $logs[0] ?? null;
    }

    /**
     * Remove a queued log by uid and type
     * 
     * @param string $uid
     * @param string $type
     */
    public function removeQueuedLogsByUid(string $uid, string $type)
    {
        $this->logQueue = array_filter($this->logQueue, function ($log) use ($uid, $type) {
            return ($uid != $log->target_uid and $log->handle != $type);
        });
    }

    /**
     * Commit a log to be saved, log will be added in the queue if $saveNow is false
     * 
     * @param  string $type
     * @param  array  $params
     * @param  bool $saveNow
     * @return bool
     */
    protected function commitLog(string $type, array $params = [], bool $saveNow = false): bool
    {
        try {
            $class = Activity::$plugin->types->getTypeClassByHandle($type);
        } catch (ActivityTypeException $e) {
            \Craft::$app->errorHandler->logException($e);
            return false;
        }
        if (!($params['request'] ?? false)) {
            $params['request'] = Activity::$plugin->logs->getCurrentRequest();
        }
        if (!($params['site'] ?? false)) {
            $params['site'] = \Craft::$app->sites->currentSite;
        }
        if (!($params['user'] ?? false)) {
            $params['user'] = \Craft::$app->user->identity;
        }
        $log = new $class($params);
        if ($saveNow) {
            return $log->save();
        }
        $this->logQueue[] = $log;
        return true;
    }

    /**
     * Empty the commited log queue, causing all the logs in the queue to be lost
     */
    public function emptyQueue()
    {
        $this->logQueue = [];
    }

    /**
     * Get the log queue
     * 
     * @return array
     */
    public function getQueue(): array
    {
        return $this->logQueue;
    }

    /**
     * Is this recorder currently recording
     * 
     * @return bool
     */
    public function getIsRecording(): bool
    {
        return $this->_isRecording;
    }

    /**
     * Get this recorder to start recording
     */
    public function startRecording()
    {
        $this->_isRecording = true;
    }

    /**
     * Get this recorder to stop recording
     */
    public function stopRecording()
    {
        $this->_isRecording = false;
    }

    /**
     * is a type ignored by the settings
     * 
     * @param  string  $type
     * @return boolean
     */
    protected function isTypeIgnored(string $type): bool
    {
        return Activity::$plugin->settings->isTypeIgnored($type);
    }

    /**
     * Should a log type be saved
     * 
     * @param  string $type
     * @return boolean
     */
    protected function shouldSaveLog(string $type): bool
    {
        $settings = Activity::$plugin->settings;
        if (!$this->isRecording or $settings->isTypeIgnored($type)) {
            return false;
        }
        return true;
    }
}