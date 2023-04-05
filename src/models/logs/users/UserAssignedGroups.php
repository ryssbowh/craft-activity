<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\logs\UserLog;

class UserAssignedGroups extends UserLog
{
    /**
     * Removed groups setter
     *
     * @param array $groups
     * @since 1.3.8
     */
    public function setRemovedGroups(array $groups)
    {
        $this->data['removedGroups'] = $groups;
    }

    /**
     * New groups setter
     *
     * @param array $groups
     * @since 1.3.8
     */
    public function setNewGroups(array $groups)
    {
        $this->data['newGroups'] = $groups;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Assigned user {title} to groups', ['title' => $this->elementTitle]);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return \Craft::$app->view->renderTemplate('activity/descriptions/user-groups', [
            'log' => $this,
            'newGroups' => $this->getGroupNames($this->data['newGroups'] ?? []),
            'removedGroups' => $this->getGroupNames($this->data['removedGroups'] ?? []),
        ]);
    }

    /**
     * Get group names from an array of data
     *
     * @param  array  $data
     * @return array
     * @since 1.3.8
     */
    protected function getGroupNames(array $data): string
    {
        $names = [];
        foreach ($data as $data) {
            $name = $data['name'];
            if ($group = \Craft::$app->userGroups->getGroupById($data['id'])) {
                $name = $group->name;
            } else {
                $name = \Craft::t('activity', '{group} (deleted)', ['group' => $name]);
            }
            $names[] = $name;
        }
        return implode(', ', $names);
    }
}
