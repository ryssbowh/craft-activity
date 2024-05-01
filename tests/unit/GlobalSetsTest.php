<?php

use craft\elements\GlobalSet;
use craft\models\FieldLayout;
use craft\models\FieldLayoutTab;

class GlobalSetsTest extends BaseTest
{
    public function testGlobalSets()
    {
        $this->resetActivity();
        $set = new GlobalSet([
            'name' => 'Test',
            'handle' => 'test',
            'fieldLayout' => new FieldLayout([
                'tabs' => [new FieldLayoutTab([
                    'name' => 'Test'
                ])]
            ])
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
