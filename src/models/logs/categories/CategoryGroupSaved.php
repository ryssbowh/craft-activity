<?php

namespace Ryssbowh\Activity\models\logs\categories;

class CategoryGroupSaved extends CategoryGroupCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved category group {name}', ['name' => $this->modelName]);
    }
}