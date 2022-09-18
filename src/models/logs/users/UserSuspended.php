<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\UserLog;

class UserSuspended extends UserLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Suspended user {user}', ['user' => $this->elementTitle]);
    }
}