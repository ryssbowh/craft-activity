<?php

use Ryssbowh\Activity\Activity;
use craft\helpers\StringHelper;

class RoutesTest extends BaseTest
{
    public function _before()
    {
        Activity::$plugin->settings->ignoreRules = [];
    }

    public function testRoutes()
    {
        $this->resetActivity();
        $uid = \Craft::$app->getRoutes()->saveRoute([], '');
        $this->assertIsString($uid);
        $this->assertLogCount(1);
        $this->assertLatestLog('routeCreated');
        \Craft::$app->getRoutes()->saveRoute([], '', null, $uid);
        $this->assertLogCount(2);
        $this->assertLatestLog('routeSaved');
        $this->assertTrue(\Craft::$app->getRoutes()->deleteRouteByUid($uid));
        $this->assertLogCount(3);
        $this->assertLatestLog('routeDeleted');
    }
}
