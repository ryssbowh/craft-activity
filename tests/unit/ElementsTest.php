<?php

use Ryssbowh\Activity\Activity;
use craft\base\Element;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\User;
use yii\base\Event;

class ElementsTest extends BaseTest
{
    public function testUsers()
    {
        $this->resetActivity();
        $user = new User([
            'username' => 'username',
            'email' => 'test@test.com'
        ]);
        $this->assertTrue(\Craft::$app->elements->saveElement($user));
        $this->assertLogCount(1);
        $this->assertLatestLog('userRegistered');
        $user->firstName = 'test';
        $this->assertTrue(\Craft::$app->elements->saveElement($user));
        $this->assertLogCount(2);
        $this->assertLatestLog('userSaved');
        $this->assertTrue(\Craft::$app->elements->deleteElement($user));
        $this->assertLogCount(3);
        $this->assertLatestLog('userDeleted');
        $this->assertTrue(\Craft::$app->elements->restoreElement($user));
        $this->assertLogCount(4);
        $this->assertLatestLog('userRestored');
    }

    public function testCategories()
    {
        $this->resetActivity();
        $category = $this->createCategory();
        $this->assertTrue(\Craft::$app->elements->saveElement($category));
        $this->assertLogCount(1);
        $this->assertLatestLog('categoryCreated');
        $category->title = 'test 2';
        $this->assertTrue(\Craft::$app->elements->saveElement($category));
        $this->assertLogCount(2);
        $this->assertLatestLog('categorySaved');
        $this->assertTrue(\Craft::$app->elements->deleteElement($category));
        $this->assertLogCount(3);
        $this->assertLatestLog('categoryDeleted');
        $this->assertTrue(\Craft::$app->elements->restoreElement($category));
        $this->assertLogCount(4);
        $this->assertLatestLog('categoryRestored');
    }

    public function testAssets()
    {
        $this->resetActivity();
        $asset = $this->createAsset();
        $this->assertTrue(\Craft::$app->elements->saveElement($asset));
        $this->assertLogCount(1);
        $this->assertLatestLog('assetCreated');
        $asset->title = 'test 2';
        $asset->setScenario(Element::SCENARIO_ESSENTIALS);
        $this->assertTrue(\Craft::$app->elements->saveElement($asset));
        $this->assertLogCount(2);
        $this->assertLatestLog('assetSaved');
        $this->assertTrue(\Craft::$app->elements->deleteElement($asset));
        $this->assertLogCount(3);
        $this->assertLatestLog('assetDeleted');
    }

    public function testEntries()
    {
        $this->resetActivity();
        $entry = $this->createEntry();
        $this->assertTrue(\Craft::$app->elements->saveElement($entry));
        $this->assertLogCount(1);
        $this->assertLatestLog('entryCreated');
        $entry->title = 'test 2';
        $this->assertTrue(\Craft::$app->elements->saveElement($entry));
        $this->assertLogCount(2);
        $this->assertLatestLog('entrySaved');
        $this->assertTrue(\Craft::$app->elements->deleteElement($entry));
        $this->assertLogCount(3);
        $this->assertLatestLog('entryDeleted');
        $this->assertTrue(\Craft::$app->elements->restoreElement($entry));
        $this->assertLogCount(4);
        $this->assertLatestLog('entryRestored');
    }

    public function testGlobals()
    {
        $this->resetActivity();
        $global = \Craft::$app->globals->getSetByHandle('global');
        $global->setFieldValue('category', []);
        $this->assertTrue(\Craft::$app->elements->saveElement($global));
        $this->assertLogCount(1);
        $this->assertLatestLog('globalSaved');
    }

    protected function createEntry()
    {
        $section = \Craft::$app->sections->getSectionByHandle('channel');
        $entryTypes = $section->getEntryTypes();
        $entryType = reset($entryTypes);
        $user = User::find()->one();
        return new Entry([
            'sectionId' => $section->id,
            'typeId' => $entryType->id,
            'fieldLayoutId' => $entryType->fieldLayoutId,
            'authorId' => $user->id,
            'title' => 'My Entry',
            'slug' => 'my-entry',
            'postDate' => new DateTime(),
        ]);
    }

    protected function createAsset()
    {
        $volume = \Craft::$app->volumes->getVolumeByHandle('public');
        $folder = \Craft::$app->assets->getRootFolderByVolumeId($volume->id);
        $asset = new Asset();
        $asset->setScenario(Asset::SCENARIO_CREATE);
        $src = \Craft::getAlias('@storage/file.svg');
        $dest = \Craft::getAlias('@storage/runtime/temp/file.svg');
        copy($src, $dest);
        $asset->tempFilePath = $dest;
        $asset->filename = 'test.svg';
        $asset->newFolderId = $folder->id;
        $asset->volumeId = $folder->volumeId;
        $asset->avoidFilenameConflicts = true;
        return $asset;
    }

    protected function createCategory()
    {
        $group = \Craft::$app->categories->getGroupByHandle('category');
        return new Category([
            'groupId' => $group->id,
            'title' => 'test'
        ]);
    }
}
