<?php

namespace Ryssbowh\Activity\models\logs\assets;

class AssetTransformDeleted extends AssetTransformCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted asset transform {name}', ['name' => $this->target_name]);
    }
}