<?php

use craft\models\Site;

class SitesTest extends BaseTest
{
    public function testSites()
    {
        $this->resetActivity();
        $site = new Site([
            'name' => 'Test',
            'handle' => 'test',
            'language' => 'en-GB',
            'groupId' => \Craft::$app->sites->getAllGroups()[0]->id
        ]);
        $this->assertTrue(\Craft::$app->sites->saveSite($site));
        $this->assertLogCount(1);
        $this->assertLatestLog('siteCreated');
        $site->name = 'Test 2';
        $this->assertTrue(\Craft::$app->sites->saveSite($site));
        $this->assertLogCount(2);
        $this->assertLatestLog('siteSaved');
        $this->assertTrue(\Craft::$app->sites->deleteSite($site));
        $this->assertLogCount(3);
        $this->assertLatestLog('siteDeleted');
    }
}
