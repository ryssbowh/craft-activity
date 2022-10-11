<?php

use craft\models\CategoryGroup;
use craft\models\CategoryGroup_SiteSettings;

class CategoryGroupsTest extends BaseTest
{
    public function testCategoryGroup()
    {
        $this->resetActivity();
        $settings = new CategoryGroup_SiteSettings([
            'siteId' => 1
        ]);
        $group = new CategoryGroup([
            'name' => 'Test',
            'handle' => 'test',
            'siteSettings' => [1 => $settings]
        ]);
        $this->assertTrue(\Craft::$app->categories->saveGroup($group));
        $this->assertLogCount(1);
        $this->assertLatestLog('categoryGroupCreated');
        $group->name = 'Test 2';
        $this->assertTrue(\Craft::$app->categories->saveGroup($group));
        $this->assertLogCount(2);
        $this->assertLatestLog('categoryGroupSaved');
        $this->assertTrue(\Craft::$app->categories->deleteGroup($group));
        $this->assertLogCount(3);
        $this->assertLatestLog('categoryGroupDeleted');
    }
}
