<?php

use craft\fields\PlainText;

class FieldsTest extends BaseTest
{
    public function testFields()
    {
        $this->resetActivity();
        $field = new PlainText([
            'name' => 'Test',
            'handle' => 'test'
        ]);
        $this->assertTrue(\Craft::$app->fields->saveField($field));
        $this->assertLogCount(1);
        $this->assertLatestLog('fieldCreated');
        $field->name = 'Test 2';
        $this->assertTrue(\Craft::$app->fields->saveField($field));
        $this->assertLogCount(2);
        $this->assertLatestLog('fieldSaved');
        $this->assertTrue(\Craft::$app->fields->deleteField($field));
        $this->assertLogCount(3);
        $this->assertLatestLog('fieldDeleted');
    }
}
