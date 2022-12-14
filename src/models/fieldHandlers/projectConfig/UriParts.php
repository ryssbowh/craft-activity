<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class UriParts extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $parts = [];
        foreach ($this->value as $value) {
            $parts[] = is_array($value) ? ('{' . $value[0] . '}') : $value;
        }
        $this->fancyValue = implode($parts);
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
            ProjectConfig::PATH_ROUTES . '.{uid}.uriParts'
        ];
    }

    /**
     * Get a site name by uid
     * 
     * @param  string $uid
     * @return string
     */
    protected function getSiteName(string $uid): string
    {
        if ($uid) {
            $site = \Craft::$app->sites->getSiteByUid($uid);
            return $site ? $site->name : '';
        }
        return '';
    }
}