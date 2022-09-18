<?php

namespace Ryssbowh\Activity\base;

abstract class BackupLog extends ActivityLog
{
    public $file;

    /**
     * @inheritDoc
     */
    public function getDbData(): array
    {
        return array_merge(parent::getDbData(), [
            'file' => $this->file
        ]);
    }
}