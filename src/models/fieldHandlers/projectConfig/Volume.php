<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

class Volume extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init()
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
    public static function getTargets(): array
    {
        return [
            'users.photoVolumeUid',
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