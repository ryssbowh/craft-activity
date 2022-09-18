<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\ActivityLog;
use craft\helpers\UrlHelper;
use craft\models\UserGroup;

class UserGroupPermissionsChanged extends ActivityLog
{   
    public $group;

    protected $_group;

    /**
     * @inheritDoc
     */
    public function getDbData(): array
    {
        return array_merge(parent::getDbData(), [
            'target_class' => get_class($this->group),
            'target_id' => $this->group->id,
            'target_name' => $this->group->name,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Changed permissions for user group {name}', ['name' => $this->groupName]);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return \Craft::$app->view->renderTemplate('activity/descriptions/permissions', [
            'log' => $this
        ]);
    }

    /**
     * Get the group name
     * 
     * @return string
     */
    public function getGroupName(): string
    {
        if ($this->groupModel) {
            return '<a href="' . UrlHelper::cpUrl('settings/users/groups/' . $this->groupModel->id) . '" target="_blank">' . $this->groupModel->name . '</a>';
        }
        return $this->target_name;
    }

    /**
     * Get the group model
     * 
     * @return ?UserGroup
     */
    public function getGroupModel(): ?UserGroup
    {
        if ($this->_group === null and $this->target_id) {
            $this->_group = \Craft::$app->userGroups->getGroupById($this->target_id);
        }
        return $this->_group;
    }
}