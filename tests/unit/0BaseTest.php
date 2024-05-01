<?php

use Codeception\Test\Unit;
use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\records\ActivityLog;

class BaseTest extends Unit
{
    protected function _before()
    {
        $this->deleteLogs();
        $this->emptyQueues();
    }

    protected function saveLogs()
    {
        codecept_debug('saving all activity logs');
        Activity::$plugin->recorders->saveLogs();
    }

    protected function stopRecording()
    {
        codecept_debug('stop recording');
        Activity::$plugin->recorders->stopRecording();
    }

    protected function startRecording()
    {
        codecept_debug('start recording');
        Activity::$plugin->recorders->startRecording();
    }

    protected function emptyQueues()
    {
        codecept_debug('empty queues');
        Activity::$plugin->recorders->emptyQueues();
    }

    protected function deleteLogs()
    {
        codecept_debug('deleting all activity logs');
        ActivityLog::deleteAll();
    }

    protected function countLogs()
    {
        return ActivityLog::find()->count();
    }

    protected function assertLogCount(int $number)
    {
        $this->saveLogs();
        $this->assertEquals($number, $this->countLogs());
    }

    protected function assertLatestLog($types)
    {
        $this->saveLogs();
        $types = is_array($types) ? $types : [$types];
        $logs = ActivityLog::find()->orderBy('id desc')->limit(sizeof($types))->all();
        foreach ($types as $i => $type) {
            $handle = isset($logs[$i]) ? $logs[$i]->type : null;
            $this->assertEquals($type, $handle);
        }
    }

    protected function resetActivity()
    {
        Activity::$plugin->recorders->registered = false;
        Activity::$plugin->recorders->register();
    }
}
