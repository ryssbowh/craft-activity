<?php

use craft\base\Volume;
use craft\volumes\Temp;
use yii\base\Event;

class VolumesTest extends BaseTest
{
    public function testVolumes()
    {
        $this->resetActivity();
        Event::on(Volume::class, Volume::EVENT_DEFINE_RULES, function ($e) {
            //Removing the path rule to avoid the error "Local volumes cannot be located within system directories"
            $rules = [];
            foreach ($e->rules as $rule) {
                if ($rule[0][0] != 'path') {
                    $rules[] = $rule;
                }
            }
            $e->rules = $rules;
        });
        $volume = \Craft::$app->volumes->createVolume([
            'type' => Temp::class,
            'name' => 'Test',
            'handle' => 'test'
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
