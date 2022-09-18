<?php

namespace Ryssbowh\Activity\models\logs\categories;

class CategoryGroupDeleted extends CategoryGroupCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted category group {name}', ['name' => $this->target_name]);
    }
}