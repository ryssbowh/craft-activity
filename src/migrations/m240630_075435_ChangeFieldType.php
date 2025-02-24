<?php

namespace Ryssbowh\Activity\migrations;

use Craft;
use craft\db\Migration;

/**
 * m240630_075435_ChangeFieldType migration.
 */
class m240630_075435_ChangeFieldType extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $type = $this->getDb()->getDriverName() === 'pgsql' ? $this->text() : 'longtext';
        $this->alterColumn('{{%activity_changed_fields}}', 'data', $type);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->alterColumn('{{%activity_changed_fields}}', 'data', $this->text());
        return true;
    }
}
