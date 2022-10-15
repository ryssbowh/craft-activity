<?php

namespace Ryssbowh\Activity\models\logs\assets;

class ImageTransformDeleted extends ImageTransformCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted image transform {name}', ['name' => $this->target_name]);
    }
}