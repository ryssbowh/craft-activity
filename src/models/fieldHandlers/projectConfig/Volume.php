<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class Volume extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if ($this->value) {
            $this->fancyValue = $this->getVolumeName($this->value);
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
            ProjectConfig::PATH_USERS . '.photoVolumeUid',
            'assets.tempVolumeUid'
        ];
    }

    /**
     * Get a volume name by uid
     * 
     * @param  string $uid
     * @return string
     */
    protected function getVolumeName(string $uid): string
    {
        $volume = \Craft::$app->volumes->getVolumeByUid($uid);
        return $volume ? $volume->name : '';
    }
}