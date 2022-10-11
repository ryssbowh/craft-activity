<?php

use craft\models\SiteGroup;

class SiteGroupsTest extends BaseTest
{
    public function testSiteGroups()
    {
        $this->resetActivity();
        $group = new SiteGroup([
            'name' => 'Test'
        ]);
        $this->assertTrue(\Craft::$app->sites->saveGroup($group));
        $this->assertLogCount(1);
        $this->assertLatestLog('siteGroupCreated');
        $group->name = 'Test 2';
        $this->assertTrue(\Craft::$app->sites->saveGroup($group));
        $this->assertLogCount(2);
        $this->assertLatestLog('siteGroupSaved');
        $this->assertTrue(\Craft::$app->sites->deleteGroup($group));
        $this->assertLogCount(3);
        $this->assertLatestLog('siteGroupDeleted');
    }
}
