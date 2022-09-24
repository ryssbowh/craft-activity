<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\logs\UserLog;

class UserLocked extends UserLog
{   
    /**
     * @var int
     */
    public $attempts;

    public function getDbData(): array
    {
        return array_merge(parent::getDbData(), [
            'attempts' => $this->attempts
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Was locked after {number} login attempt', ['number' => $this->data['attempts']]);
    }
}