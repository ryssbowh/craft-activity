<?php

use craft\models\Section;
use craft\models\Section_SiteSettings;

class SectionsTest extends BaseTest
{
    public function testSections()
    {
        $this->resetActivity();
        $siteId = \Craft::$app->sites->getPrimarySite()->id;
        $settings = new Section_SiteSettings([
            'siteId' => $siteId
        ]);
        $section = new Section([
            'name' => 'Test',
            'handle' => 'test',
            'siteSettings' => [$siteId => $settings],
            'type' => 'channel',
            'entryTypes' => [\Craft::$app->entries->getAllEntryTypes()[0]]
        ]);
        $this->assertTrue(\Craft::$app->entries->saveSection($section));
        $this->assertLogCount(1);
        $this->assertLatestLog('sectionCreated');
        $section->name = 'Test 2';
        $this->assertTrue(\Craft::$app->entries->saveSection($section));
        $this->assertLogCount(2);
        $this->assertLatestLog('sectionSaved');
        $this->assertTrue(\Craft::$app->entries->deleteSection($section));
        $this->assertLogCount(3);
        $this->assertLatestLog(['sectionDeleted']);
    }
}
