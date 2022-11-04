<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

/**
 * @since 1.2.0
 */
class Transform extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if ($this->value) {
            $transform = \Craft::$app->assetTransforms->getTransformByUid($this->value);
            $this->fancyValue = $transform ? $transform->name : null;
        }
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }
}