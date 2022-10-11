<?php

use craft\models\Section;
use craft\models\Section_SiteSettings;

class SectionsTest extends BaseTest
{
    public function testSections()
    {
        $this->resetActivity();
        $settings = new Section_SiteSettings([
            'siteId' => 1
        ]);
        $section = new Section([
            'name' => 'Test',
            'handle' => 'test',
            'siteSettings' => [1 => $settings],
            'type' => 'channel'
        ]);
        $this->assertTrue(\Craft::$app->sections->saveSection($section));
        $this->assertLogCount(2);
        $this->assertLatestLog(['entryTypeCreated', 'sectionCreated'], 2);
        $section->name = 'Test 2';
        $this->assertTrue(\Craft::$app->sections->saveSection($section));
        $this->assertLogCount(3);
        $this->assertLatestLog('sectionSaved');
        $this->assertTrue(\Craft::$app->sections->deleteSection($section));
        $this->assertLogCount(5);
        $this->assertLatestLog(['entryTypeDeleted', 'sectionDeleted']);
    }
}
