<?php

namespace Ryssbowh\Activity\models\logs\assets;

class VolumeSaved extends VolumeCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved volume {name}', ['name' => $this->modelName]);
    }
}