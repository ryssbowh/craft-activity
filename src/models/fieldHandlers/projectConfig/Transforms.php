<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

/**
 * @since 2.2.0
 */
class Transforms extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if ($this->value == '*') {
            $this->fancyValue = \Craft::t('app', 'All');
        } elseif ($this->value) {
            $this->fancyValue = [];
            foreach ($this->value as $uid) {
                $this->fancyValue[] = $this->getTransformName($uid);
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
     * Get a transform name by uid
     *
     * @param  string $uid
     * @return string
     */
    protected function getTransformName(string $uid): string
    {
        $transform = \Craft::$app->imageTransforms->getTransformByUid($uid);
        return $transform ? $transform->name : '';
    }
}
