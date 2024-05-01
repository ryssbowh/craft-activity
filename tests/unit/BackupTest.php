<?php

use Ryssbowh\Activity\records\ActivityLog;

class BackupTest extends BaseTest
{
    public function testCreatingBackup()
    {
        $db = \Craft::$app->getDb();
        $db->backupTo(\Craft::getAlias('@storage/db-backups/test.sql'));
        $this->assertLogCount(1);
        $this->assertLatestLog('backupCreated');
    }
}
