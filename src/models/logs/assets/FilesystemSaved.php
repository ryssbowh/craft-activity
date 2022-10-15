<?php

namespace Ryssbowh\Activity\models\logs\assets;

class FilesystemSaved extends FilesystemCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved filesystem {name}', ['name' => $this->modelName]);
    }
}