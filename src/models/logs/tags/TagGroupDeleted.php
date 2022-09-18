<?php

namespace Ryssbowh\Activity\models\logs\tags;

class TagGroupDeleted extends TagGroupCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted tag group {name}', ['name' => $this->target_name]);
    }
}