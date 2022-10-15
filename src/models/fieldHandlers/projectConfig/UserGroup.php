<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class UserGroup extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
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
    public static function getTargets(): array
    {
        return [
            ProjectConfig::PATH_USER_GROUPS
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