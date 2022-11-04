<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\Fields;

/**
 * @since 1.2.0
 */
class Sources extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if ($this->value == '*') {
            $this->fancyValue = \Craft::t('app', 'All');
        } else {
            $this->fancyValue = [];
            foreach ($this->value as $source) {
                $this->fancyValue[] = $this->getSourceName($source);
            }
            $this->fancyValue = implode(', ', $this->fancyValue);
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
            Fields::CONFIG_FIELDS_KEY . '.{uid}.settings[craft\\fields\\Assets].sources',
            Fields::CONFIG_FIELDS_KEY . '.{uid}.settings[craft\\fields\\Entries].sources',
            Fields::CONFIG_FIELDS_KEY . '.{uid}.settings[craft\\fields\\Users].sources'
        ];
    }

    /**
     * Get a source name
     * 
     * @param  string $source
     * @return string
     */
    protected function getSourceName(string $source): string
    {
        $elems = explode(':', $source);
        if (sizeof($elems) == 1) {
            return \Craft::t('app', ucfirst($elems[0]));
        } elseif ($elems[0] == 'volume') {
            $model = \Craft::$app->volumes->getVolumeByUid($elems[1] ?? '');
            return $model ? $model->name : $source;
        } elseif ($elems[0] == 'section') {
            $model = \Craft::$app->sections->getSectionByUid($elems[1] ?? '');
            return $model ? $model->name : $source;
        } elseif ($elems[0] == 'group') {
            $model = \Craft::$app->userGroups->getGroupByUid($elems[1] ?? '');
            return $model ? $model->name : $source;
        }
        return $source;
    }
}