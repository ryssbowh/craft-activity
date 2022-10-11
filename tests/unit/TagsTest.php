<?php

use craft\models\TagGroup;

class TagsTest extends BaseTest
{
    public function testTags()
    {
        $this->resetActivity();
        $group = new TagGroup([
            'name' => 'Test',
            'handle' => 'test'
        ]);
        $this->assertTrue(\Craft::$app->tags->saveTagGroup($group));
        $this->assertLogCount(1);
        $this->assertLatestLog('tagGroupCreated');
        $group->name = 'Test 2';
        $this->assertTrue(\Craft::$app->tags->saveTagGroup($group));
        $this->assertLogCount(2);
        $this->assertLatestLog('tagGroupSaved');
        $this->assertTrue(\Craft::$app->tags->deleteTagGroup($group));
        $this->assertLogCount(3);
        $this->assertLatestLog('tagGroupDeleted');
    }
}
