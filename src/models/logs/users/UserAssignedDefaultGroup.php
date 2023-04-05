<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\logs\UserLog;

class UserAssignedDefaultGroup extends UserLog
{
    /**
     * Group setter
     *
     * @param array $group
     */
    public function setGroup(array $group)
    {
        $this->data['group'] = $group;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        $name = $this->data['group']['name'];
        if ($group = \Craft::$app->userGroups->getGroupById($this->data['group']['id'])) {
            $name = $group->name;
        } else {
            $name .= ' (deleted)';
        }
        return \Craft::t('activity', 'Was assigned to default group {group}', ['group' => $name]);
    }
}
