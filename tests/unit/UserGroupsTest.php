<?php

use craft\models\UserGroup;

class UserGroupsTest extends BaseTest
{
    public function testUserGroups()
    {
        $this->resetActivity();
        $group = new UserGroup([
            'name' => 'Test',
            'handle' => 'test'
        ]);
        $this->assertTrue(\Craft::$app->userGroups->saveGroup($group));
        $this->assertLogCount(2);
        $this->assertLatestLog(['userGroupPermissionsSaved', 'userGroupCreated']);
        $group->name = 'Test 2';
        $this->assertTrue(\Craft::$app->userGroups->saveGroup($group));
        $this->assertLogCount(4);
        $this->assertLatestLog(['userGroupPermissionsSaved', 'userGroupSaved']);
        $this->assertTrue(\Craft::$app->userGroups->deleteGroup($group));
        $this->assertLogCount(5);
        $this->assertLatestLog('userGroupDeleted');
    }
}
