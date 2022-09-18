<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\UserLog;

class UserAssignedDefaultGroup extends UserLog
{
    public $group = '';

    /**
     * @inheritDoc
     */
    public function getDbData(): array
    {
        return array_merge(parent::getDbData(), [
            'data' => $this->group,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Was assigned to default group {group}', ['group' => $this->data['group']]);
    }
}