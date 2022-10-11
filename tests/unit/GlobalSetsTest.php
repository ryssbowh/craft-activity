<?php

use craft\elements\GlobalSet;

class GlobalSetsTest extends BaseTest
{
    public function testGlobalSets()
    {
        $this->resetActivity();
        $set = new GlobalSet([
            'name' => 'Test',
            'handle' => 'test'
        ]);
        $this->assertTrue(\Craft::$app->globals->saveSet($set));
        $this->assertLogCount(1);
        $this->assertLatestLog('globalSetCreated');
        $set->name = 'Test 2';
        $this->assertTrue(\Craft::$app->globals->saveSet($set));
        $this->assertLogCount(2);
        $this->assertLatestLog('globalSetSaved');
        \Craft::$app->globals->deleteSet($set);
        $this->assertLogCount(3);
        $this->assertLatestLog('globalSetDeleted');
    }
}
