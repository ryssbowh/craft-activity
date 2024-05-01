<?php

use Ryssbowh\Activity\records\ActivityLog;

class BackupTest extends BaseTest
{
    public function testCreatingBackup()
    {
        $db = \Craft::$app->getDb();
        $db->backupTo(\Craft::getAlias('@storage/db-backups/test.sql'));
        // $this->saveLogs();
        // codecept_debug(ActivityLog::find()->all()[1]->type);
        $this->assertLogCount(1);
        $this->assertLatestLog('backupCreated');
    }
}
