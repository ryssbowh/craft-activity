<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\UserLog;

class UserUnlocked extends UserLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Unlocked user {user}', ['user' => $this->elementTitle]);
    }
}