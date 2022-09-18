<?php

namespace Ryssbowh\Activity\models\logs\tags;

class TagGroupSaved extends TagGroupCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved tag group {name}', ['name' => $this->modelName]);
    }
}