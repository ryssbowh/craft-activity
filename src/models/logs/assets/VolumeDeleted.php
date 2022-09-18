<?php

namespace Ryssbowh\Activity\models\logs\assets;

class VolumeDeleted extends VolumeCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted volume {name}', ['name' => $this->target_name]);
    }
}