<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\logs\UserLog;

class UserAssignedDefaultGroup extends UserLog
{
    /**
     * Group setter
     * 
     * @param string $group
     */
    public function setGroup(string $group)
    {
        $this->data = [
            'group' => $group
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Was assigned to default group {group}', ['group' => $this->data['group']]);
    }
}