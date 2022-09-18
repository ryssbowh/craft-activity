<?php

namespace Ryssbowh\Activity\models\logs\globals;

class GlobalSetSaved extends GlobalSetCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved global set {name}', ['name' => $this->modelName]);
    }
}