<?php

use craft\elements\User;
use craft\widgets\Feed;

class WidgetsTest extends BaseTest
{
    public function testWidgets()
    {
        $this->resetActivity();
        $widget = new Feed([
            'title' => 'test',
            'url' => 'http://feed.test'
        ]);
        \Craft::$app->getUser()->setIdentity(User::find()->one());
        $this->assertTrue(\Craft::$app->dashboard->saveWidget($widget));
        $this->assertLogCount(1);
        $this->assertLatestLog('widgetCreated');
        $this->assertTrue(\Craft::$app->dashboard->saveWidget($widget));
        $this->assertLogCount(2);
        $this->assertLatestLog('widgetSaved');
        $this->assertTrue(\Craft::$app->dashboard->deleteWidget($widget));
        $this->assertLogCount(3);
        $this->assertLatestLog('widgetDeleted');
    }
}
