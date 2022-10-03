<?php

namespace Ryssbowh\Activity\base\logs;

abstract class BackupLog extends ActivityLog
{
    /**
     * @var string
     */
    public $file;

    /**
     * @inheritDoc
     */
    public function getDbData(): array
    {
        return array_merge_recursive(parent::getDbData(), [
            'data' => ['file' => $this->file]
        ]);
    }
}