<?php

namespace Ryssbowh\Activity\migrations;

use craft\db\Migration;
use craft\db\Table;

class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%activity_logs}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(255)->notNull(),
            'target_uid' => $this->string(255),
            'target_class' => $this->string(255),
            'target_name' => $this->string(255),
            'user_name' => $this->string(255),
            'user_id' => $this->integer(11)->unsigned()->notNull(),
            'site_name' => $this->string(255),
            'site_id' => $this->integer(11)->unsigned()->notNull(),
            'request' => $this->string(7),
            'ip' => $this->string(50),
            'data' => $this->text(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);
        $this->createTable('{{%activity_changed_fields}}', [
            'id' => $this->primaryKey(),
            'log_id' => $this->integer(11),
            'name' => $this->string(255)->notNull(),
            'handler' => $this->string(255)->notNull(),
            'data' => $this->text(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);
        $this->addForeignKey('activity_fields_log_id_fk', '{{%activity_changed_fields}}', ['log_id'], '{{%activity_logs}}', ['id'], 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTableIfExists('{{%activity_changed_fields}}');
        $this->dropTableIfExists('{{%activity_logs}}');
    }
}
