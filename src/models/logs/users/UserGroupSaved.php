<?php

namespace Ryssbowh\Activity\models\logs\users;

class UserGroupSaved extends UserGroupCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved user group {name}', ['name' => $this->modelName]);
    }
}