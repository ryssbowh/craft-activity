<?php

namespace Ryssbowh\Activity\base\recorders;

use Ryssbowh\Activity\Activity;
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
    }

    /**
     * Commit a log to be saved, log will be added in the queue if $saveNow is false
     * 
     * @param  string $type
     * @param  array  $params
     * @param  bool $saveNow
     * @return bool
     */
    protected function commitLog(string $type, array $params, bool $saveNow = false): bool
    {
        try {
            $class = Activity::$plugin->types->getTypeClassByHandle($type);
        } catch (ActivityTypeException $e) {
            \Craft::$app->errorHandler->logException($e);
            return false;
        }
        $log = new $class($params);
        if (!$log->request and \Craft::$app->projectConfig->isApplyingYamlChanges) {
            $log->request = Logs::REQUEST_YAML;
        }
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
        if (!$this->isRecording or 
            $settings->isTypeIgnored($type) or 
            ($settings->ignoreApplyingYaml and \Craft::$app->projectConfig->isApplyingYamlChanges) or 
            ($settings->ignoreConsoleRequests and \Craft::$app->request->isConsoleRequest) or
            ($settings->ignoreCpRequests and \Craft::$app->request->isCpRequest) or
            ($settings->ignoreSiteRequests and \Craft::$app->request->isSiteRequest)
        ) {
            return false;
        }
        return true;
    }
}