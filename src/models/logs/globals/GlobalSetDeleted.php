<?php

namespace Ryssbowh\Activity\models\logs\globals;

class GlobalSetDeleted extends GlobalSetCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted global set {name}', ['name' => $this->target_name]);
    }
}