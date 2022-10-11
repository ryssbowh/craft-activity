<?php

use craft\models\EntryType;

class EntryTypesTest extends BaseTest
{
    public function testEntryTypes()
    {
        $this->resetActivity();
        $section = \Craft::$app->sections->getSectionByHandle('channel');
        $type = new EntryType([
            'name' => 'Test',
            'handle' => 'test',
            'sectionId' => $section->id
        ]);
        $this->assertTrue(\Craft::$app->sections->saveEntryType($type));
        $this->assertLogCount(1);
        $this->assertLatestLog('entryTypeCreated');
        $type->name = 'Test 2';
        $this->assertTrue(\Craft::$app->sections->saveEntryType($type));
        $this->assertLogCount(2);
        $this->assertLatestLog('entryTypeSaved');
        $this->assertTrue(\Craft::$app->sections->deleteEntryType($type));
        $this->assertLogCount(3);
        $this->assertLatestLog('entryTypeDeleted');
    }
}
