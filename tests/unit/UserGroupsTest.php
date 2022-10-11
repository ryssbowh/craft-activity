<?php

use craft\models\UserGroup;

class UserGroupsTest extends BaseTest
{
    public function testUserGroups()
    {
        $this->resetActivity();
        $group = new UserGroup([
            'name' => 'Test 2',
            'handle' => 'test2'
        ]);
        $this->assertTrue(\Craft::$app->userGroups->saveGroup($group));
        $this->assertLogCount(1);
        $this->assertLatestLog('userGroupCreated');
        $group->name = 'Test 2';
        $this->assertTrue(\Craft::$app->userGroups->saveGroup($group));
        $this->saveLogs();
        $this->assertLogCount(2);
        $this->assertLatestLog('userGroupSaved');
        $this->assertTrue(\Craft::$app->userGroups->deleteGroup($group));
        $this->assertLogCount(3);
        $this->assertLatestLog('userGroupDeleted');
    }
}
