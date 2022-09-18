<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

class Volume extends DefaultHandler
{
    public function init()
    {
        if ($this->value) {
            $this->fancyValue = $this->getVolumeName($this->value);
        }
    }

    public function hasFancyValue(): bool
    {
        return true;
    }

    public static function getTargets(): array
    {
        return [
            'users.photoVolumeUid',
            'assets.tempVolumeUid'
        ];
    }

    protected function getVolumeName(string $uid): string
    {
        $volume = \Craft::$app->volumes->getVolumeByUid($uid);
        return $volume ? $volume->name : '';
    }
}