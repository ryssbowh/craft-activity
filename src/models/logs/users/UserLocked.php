<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\logs\UserLog;

class UserLocked extends UserLog
{       
    /**
     * Attempts setter
     * 
     * @param int $attempts
     */
    public function setAttempts(int $attempts)
    {
        $this->data = [
            'attempts' => $attempts
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Was locked after {number} login attempt', ['number' => $this->data['attempts']]);
    }
}