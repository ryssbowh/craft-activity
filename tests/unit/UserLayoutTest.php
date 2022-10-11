<?php

use craft\elements\User;
use craft\models\FieldLayout;

class UserLayoutTest extends BaseTest
{
    public function testUserLayout()
    {
        $this->resetActivity();
        $fieldLayout = \Craft::$app->fields->getLayoutByType(User::class);
        \Craft::$app->getUsers()->saveLayout($fieldLayout);
        $this->assertLogCount(1);
        $this->assertLatestLog('userLayoutSaved');
    }
}
