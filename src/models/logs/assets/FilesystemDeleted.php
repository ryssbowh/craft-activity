<?php

namespace Ryssbowh\Activity\models\logs\assets;

class FilesystemDeleted extends FilesystemCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted filesystem {name}', ['name' => $this->target_name]);
    }
}