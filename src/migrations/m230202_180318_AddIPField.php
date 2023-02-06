<?php

namespace Ryssbowh\Activity\migrations;

use Craft;
use craft\db\Migration;

/**
 * m230202_180318_AddIPField migration.
 */
class m230202_180318_AddIPField extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->addColumn('{{%activity_logs}}', 'ip', $this->string(50)->after('request'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropColumn('{{%activity_logs}}', 'ip');
        return true;
    }
}
