<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\AssetTransforms as CraftAssetTransforms;
use yii\base\Event;

class AssetTransforms extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(CraftAssetTransforms::CONFIG_TRANSFORM_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('assetTransforms')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(CraftAssetTransforms::CONFIG_TRANSFORM_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('assetTransforms')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(CraftAssetTransforms::CONFIG_TRANSFORM_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('assetTransforms')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'assetTransform';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
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