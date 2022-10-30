<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class Site extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->fancyValue = $this->getSiteName($this->value);
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
            ProjectConfig::PATH_ROUTES . '.{uid}.siteUid'
        ];
    }

    /**
     * Get a site name by uid
     * 
     * @param  ?string $uid
     * @return string
     */
    protected function getSiteName(?string $uid): string
    {
        if ($uid) {
            $site = \Craft::$app->sites->getSiteByUid($uid);
            return $site ? $site->name : '';
        }
        return '';
    }
}