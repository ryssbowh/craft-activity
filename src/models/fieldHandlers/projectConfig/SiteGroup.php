<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\Sites;

class SiteGroup extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->fancyValue = $this->getGroupName($this->value);
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
            Sites::CONFIG_SITES_KEY . '.{uid}.siteGroup'
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
        $group = \Craft::$app->sites->getGroupByUid($uid);
        return $group ? $group->name : '';
    }
}