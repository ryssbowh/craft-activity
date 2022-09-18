<?php

namespace Ryssbowh\Activity\models\logs\assets;

class AssetTransformSaved extends AssetTransformCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved asset transform {name}', ['name' => $this->modelName]);
    }
}