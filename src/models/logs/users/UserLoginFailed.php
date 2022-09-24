<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\logs\UserLog;

class UserLoginFailed extends UserLog
{   
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return \Craft::t('activity', 'User failed to login');
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Failed to login');
    }
}