<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use craft\base\Model;
use craft\db\Query;
use craft\db\Table;
use craft\models\AssetTransform;
use craft\services\AssetTransforms as CraftAssetTransforms;
use yii\base\Event;

class AssetTransforms extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(CraftAssetTransforms::class, CraftAssetTransforms::EVENT_BEFORE_SAVE_ASSET_TRANSFORM, function ($event) {
            Activity::getRecorder('assetTransforms')->beforeSaved($event->assetTransform, $event->isNew);
        });
        Event::on(CraftAssetTransforms::class, CraftAssetTransforms::EVENT_AFTER_SAVE_ASSET_TRANSFORM, function ($event) {
            Activity::getRecorder('assetTransforms')->onSaved($event->assetTransform, $event->isNew);
        });
        Event::on(CraftAssetTransforms::class, CraftAssetTransforms::EVENT_AFTER_DELETE_ASSET_TRANSFORM, function ($event) {
            Activity::getRecorder('assetTransforms')->onDeleted($event->assetTransform);
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
    protected function loadOldModel(int $id): ?Model
    {
        $query = (new Query())
            ->select([
                'id',
                'name',
                'handle',
                'mode',
                'position',
                'height',
                'width',
                'format',
                'quality',
                'interlace',
                'dimensionChangeTime',
                'uid',
            ])
            ->from([Table::ASSETTRANSFORMS])
            ->where(['id' => $id])
            ->one();
        if (!$query) {
            return null;
        }
        return new AssetTransform($query);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        return ['name', 'handle', 'mode', 'position', 'width', 'height', 'quality', 'interlace', 'format'];
    }
}