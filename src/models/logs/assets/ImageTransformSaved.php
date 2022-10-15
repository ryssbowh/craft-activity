<?php

namespace Ryssbowh\Activity\models\logs\assets;

class ImageTransformSaved extends ImageTransformCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved image transform {name}', ['name' => $this->modelName]);
    }
}