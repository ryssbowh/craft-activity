<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\Routes;

class Site extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init()
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
    public static function getTargets(): array
    {
        return [
            Routes::CONFIG_ROUTES_KEY . '.{uid}.siteUid'
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