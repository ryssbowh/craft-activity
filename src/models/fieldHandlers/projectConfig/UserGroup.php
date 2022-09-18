<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

class UserGroup extends DefaultHandler
{
    public function init()
    {
        $this->fancyValue = \Craft::t('app', 'None');
        if ($this->value) {
            $this->fancyValue = $this->getGroupName($this->value);
        }
    }

    public function hasFancyValue(): bool
    {
        return true;
    }

    public static function getTargets(): array
    {
        return [
            'users.defaultGroup'
        ];
    }

    protected function getGroupName(string $uid): string
    {
        $volume = \Craft::$app->volumes->getVolumeByUid($uid);
        return $volume ? $volume->name : '';
    }
}