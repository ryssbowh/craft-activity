<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class ImageTransforms extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_IMAGE_TRANSFORMS . '.{uid}', function (Event $event) {
            Activity::getRecorder('imageTransforms')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_IMAGE_TRANSFORMS . '.{uid}', function (Event $event) {
            Activity::getRecorder('imageTransforms')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_IMAGE_TRANSFORMS . '.{uid}', function (Event $event) {
            Activity::getRecorder('imageTransforms')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'imageTransform';
    }

    /**
     * @inheritDoc
     */
    protected function _getTrackedFieldNames(): array
    {
        return ['name', 'handle', 'mode', 'position', 'width', 'height', 'quality', 'interlace', 'format'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}