<?php

namespace Ryssbowh\Activity\models\logs\users;

use Ryssbowh\Activity\base\UserLog;

class UserInvalidToken extends UserLog
{   
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return \Craft::t('activity', 'User used an invalid token');
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Used an invalid token');
    }
}