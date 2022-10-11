<?php

use FunctionalTester;
use craft\elements\User;

class PermissionsTest
{
    public function testWelcomeMessage(FunctionalTester $I)
    {
        $user = User::find()->one();
        $I->amLoggedInAs($user);
        $I->amOnPage('/pages/bob');
        $I->see('Hi Bob,');
        $I->see('Welcome to this app!');
    }
}
