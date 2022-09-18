<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\UserLog;

class UserAssignedGroups extends UserLog
{
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
            'newGroups' => $this->data['newGroups'],
            'removedGroups' => $this->data['removedGroups'],
        ]);
    }
}