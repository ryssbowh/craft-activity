<?php

namespace Ryssbowh\Activity\migrations;

use Craft;
use Ryssbowh\Activity\models\fieldHandlers\elements\LongText;
use Ryssbowh\Activity\records\ActivityChangedField;
use craft\db\Migration;

/**
 * m221026_053038_ChangedRedactorHandler migration.
 */
class m221026_053038_ChangedRedactorHandler extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $fields = ActivityChangedField::find()->all();
        foreach ($fields as $field) {
            $save = false;
            if ($field->handler == 'Ryssbowh\\Activity\\models\\fieldHandlers\\elements\\Redactor') {
                $field->handler = LongText::class;
                $save = true;
            }
            if (strpos($field->data, 'Ryssbowh\\\\Activity\\\\models\\\\fieldHandlers\\\\elements\\\\Redactor') !== false) {
                $field->data = str_replace('Ryssbowh\\\\Activity\\\\models\\\\fieldHandlers\\\\elements\\\\Redactor', 'Ryssbowh\\\\Activity\\\\models\\\\fieldHandlers\\\\elements\\\\LongText', $field->data);
                $save = true;
            }
            if ($save) {
                $field->save(false);
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m221026_053038_ChangedRedactorHandler cannot be reverted.\n";
        return false;
    }
}
