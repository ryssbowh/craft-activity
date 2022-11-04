<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

/**
 * @since 1.2.0
 */
class Source extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if ($this->value) {
            $this->fancyValue = $this->getSourceName($this->value);
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
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\fields\\Assets].restrictedLocationSource',
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\fields\\Categories].source',
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\fields\\Tags].source',
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
        if ($elems[0] == 'volume') {
            $model = \Craft::$app->volumes->getVolumeByUid($elems[1] ?? '');
            return $model ? $model->name : $source;
        } elseif ($elems[0] == 'group') {
            $model = \Craft::$app->categories->getGroupByUid($elems[1] ?? '');
            return $model ? $model->name : $source;
        } elseif ($elems[0] == 'taggroup') {
            $model = \Craft::$app->tags->getTagGroupByUid($elems[1] ?? '');
            return $model ? $model->name : $source;
        }
        return $source;
    }
}