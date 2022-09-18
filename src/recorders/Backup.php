<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\Recorder;
use craft\db\Connection;
use yii\base\Event;

class Backup extends Recorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(Connection::class, Connection::EVENT_AFTER_CREATE_BACKUP, function ($event) {
            Activity::getRecorder('backup')->onBackup($event->file);
        });
        Event::on(Connection::class, Connection::EVENT_AFTER_RESTORE_BACKUP, function ($event) {
            Activity::getRecorder('backup')->onRestore($event->file);
        });
    }
        
    /**
     * Record log when a backup is done
     * 
     * @param string $file
     */
    public function onBackup(string $file)
    {
        if (!$this->shouldSaveLog('backupCreated')) {
            return;
        }
        $this->saveLog('backupCreated', [
            'data' => ['file' => str_replace(\Craft::getAlias('@root/'), '', $file)]
        ]);
    }

    /**
     * Record log when a backup is restored
     * 
     * @param string $file
     */
    public function onRestore(string $file)
    {
        if (!$this->shouldSaveLog('backupRestored')) {
            return;
        }
        $this->saveLog('backupRestored', [
            'data' => ['file' => str_replace(\Craft::getAlias('@root/'), '', $file)]
        ]);
    }
}