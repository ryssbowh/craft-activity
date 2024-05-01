<?php

use craft\models\Volume;
use yii\base\Event;

class VolumesTest extends BaseTest
{
    public function testVolumes()
    {
        $this->resetActivity();
        $volume = new Volume([
            'name' => 'Test',
            'handle' => 'test',
            'subpath' => 'test',
            'fs' => \Craft::$app->fs->getFilesystemByHandle('default')
        ]);
        $this->assertTrue(\Craft::$app->volumes->saveVolume($volume));
        $this->assertLogCount(1);
        $this->assertLatestLog('volumeCreated');
        $volume->name = 'Test 2';
        $this->assertTrue(\Craft::$app->volumes->saveVolume($volume));
        $this->assertLogCount(2);
        $this->assertLatestLog('volumeSaved');
        $this->assertTrue(\Craft::$app->volumes->deleteVolume($volume));
        $this->assertLogCount(3);
        $this->assertLatestLog('volumeDeleted');
    }
}
