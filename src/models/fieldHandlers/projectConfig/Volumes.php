<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

/**
 * @since 1.2.0
 */
class Volumes extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if ($this->value == '*') {
            $this->fancyValue = \Craft::t('app', 'All');
        } else if (is_array($this->value)) {
            $this->fancyValue = [];
            foreach ($this->value as $uid) {
                $this->fancyValue[] = $this->getVolumeName($uid);
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