<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\logs\UserLog;

class UserUnsuspended extends UserLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Unsuspended user {user}', ['user' => $this->elementTitle]);
    }
}