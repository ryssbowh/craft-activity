<?php

class SettingsTest extends BaseTest
{
    public function testChangingAssetsSettings()
    {
        $this->resetActivity();
        \Craft::$app->projectConfig->set('assets.tempSubpath', 'test');
        $this->assertLogCount(1);
        $this->assertLatestLog('assetSettingsChanged');
    }

    public function testChangingEmailSettings()
    {
        $this->resetActivity();
        \Craft::$app->projectConfig->set('email.fromEmail', 'test');
        $this->assertLogCount(1);
        $this->assertLatestLog('emailSettingsChanged');
    }

    public function testChangingGeneralSettings()
    {
        $this->resetActivity();
        \Craft::$app->projectConfig->set('system.live', false);
        $this->assertLogCount(1);
        $this->assertLatestLog('generalSettingsChanged');
    }

    public function testChangingUserSettings()
    {
        $this->resetActivity();
        \Craft::$app->projectConfig->set('users.requireEmailVerification', false);
        $this->assertLogCount(1);
        $this->assertLatestLog('userSettingsChanged');
    }
}
