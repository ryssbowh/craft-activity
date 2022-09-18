<?php

namespace Ryssbowh\Activity\models\logs\users;

class UserGroupDeleted extends UserGroupCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted user group {name}', ['name' => $this->target_name]);
    }
}