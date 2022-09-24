<?php

namespace Ryssbowh\Activity\models\logs\backups;

use Ryssbowh\Activity\base\logs\BackupLog;

class BackupRestored extends BackupLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Restored database from backup: {file}', [
            'file' => $this->data['file']
        ]);
    }
}