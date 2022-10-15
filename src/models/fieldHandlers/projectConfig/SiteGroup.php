<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class SiteGroup extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
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
    public static function getTargets(): array
    {
        return [
            ProjectConfig::PATH_SITES . '.{uid}.siteGroup'
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