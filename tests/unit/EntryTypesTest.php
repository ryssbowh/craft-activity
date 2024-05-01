<?php

use craft\models\EntryType;

class EntryTypesTest extends BaseTest
{
    public function testEntryTypes()
    {
        $this->resetActivity();
        $type = new EntryType([
            'name' => 'Test',
            'handle' => 'test'
        ]);
        $this->assertTrue(\Craft::$app->entries->saveEntryType($type));
        $this->assertLogCount(1);
        $this->assertLatestLog('entryTypeCreated');
        $type->name = 'Test 2';
        $this->assertTrue(\Craft::$app->entries->saveEntryType($type));
        $this->assertLogCount(2);
        $this->assertLatestLog('entryTypeSaved');
        $this->assertTrue(\Craft::$app->entries->deleteEntryType($type));
        $this->assertLogCount(3);
        $this->assertLatestLog('entryTypeDeleted');
    }
}
