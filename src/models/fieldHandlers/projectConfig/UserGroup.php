<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\Users;

class UserGroup extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->fancyValue = \Craft::t('app', 'None');
        if ($this->value) {
            $this->fancyValue = $this->getGroupName($this->value);
        }
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            Users::CONFIG_USERS_KEY . '.defaultGroup'
        ];
    }

    /**
     * Get a group name by uid
     * 
     * @param  string $uid
     * @return string
     */
    protected function getGroupName(string $uid): string
    {
        $group = \Craft::$app->userGroups->getGroupByUid($uid);
        return $group ? $group->name : '';
    }
}