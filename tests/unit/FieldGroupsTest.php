<?php

use craft\models\FieldGroup;

class FieldGroupsTest extends BaseTest
{
    public function testFieldGroups()
    {
        $this->resetActivity();
        $group = new FieldGroup([
            'name' => 'Test'
        ]);
        $this->assertTrue(\Craft::$app->fields->saveGroup($group));
        $this->assertLogCount(1);
        $this->assertLatestLog('fieldGroupCreated');
        $group->name = 'Test 2';
        $this->assertTrue(\Craft::$app->fields->saveGroup($group));
        $this->assertLogCount(2);
        $this->assertLatestLog('fieldGroupSaved');
        $this->assertTrue(\Craft::$app->fields->deleteGroup($group));
        $this->assertLogCount(3);
        $this->assertLatestLog('fieldGroupDeleted');
    }
}
