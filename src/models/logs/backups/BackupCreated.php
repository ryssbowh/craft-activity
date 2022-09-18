<?php

namespace Ryssbowh\Activity\models\logs\backups;

use Ryssbowh\Activity\base\BackupLog;

class BackupCreated extends BackupLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created database backup: {file}', [
            'file' => $this->data['file']
        ]);
    }
}