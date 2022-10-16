<?php

use craft\fs\Local;
use yii\base\Event;

class FilesystemsTest extends BaseTest
{
    public function testFilesystems()
    {
        $this->resetActivity();
        $fs = \Craft::$app->fs->createFilesystem([
            'name' => 'Test',
            'handle' => 'test',
            'type' => Local::class,
            'path' => '@webroot/files'
        ]);
        $this->assertTrue(\Craft::$app->fs->saveFilesystem($fs));
        $this->assertLogCount(1);
        $this->assertLatestLog('filesystemCreated');
        $fs->name = 'Test 2';
        $this->assertTrue(\Craft::$app->fs->saveFilesystem($fs));
        $this->assertLogCount(2);
        $this->assertLatestLog('filesystemSaved');
        $this->assertTrue(\Craft::$app->fs->removeFilesystem($fs));
        $this->assertLogCount(3);
        $this->assertLatestLog('filesystemDeleted');
    }
}
