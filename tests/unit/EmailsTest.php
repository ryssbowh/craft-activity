<?php

use craft\elements\User;

class EmailsTest extends BaseTest
{
    public function testSendEmail()
    {
        $this->resetActivity();
        $mail = \Craft::$app->getMailer()
            ->composeFromKey('test_email', [
                'settings' => '',
                'user' => User::find()->one()
            ])
            ->setTo('test@test.com');

        $this->assertTrue($mail->send());
        $this->assertLogCount(1);
        $this->assertLatestLog('emailSent');
    }
}
