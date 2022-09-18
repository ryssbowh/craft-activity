<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use craft\base\Model;
use craft\elements\GlobalSet;
use craft\services\Globals;
use yii\base\Event;

class GlobalSets extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(Globals::class, Globals::EVENT_BEFORE_SAVE_GLOBAL_SET, function ($event) {
            Activity::getRecorder('globalSets')->beforeSaved($event->globalSet, $event->isNew);
        });
        Event::on(Globals::class, Globals::EVENT_AFTER_SAVE_GLOBAL_SET, function ($event) {
            Activity::getRecorder('globalSets')->onSaved($event->globalSet, $event->isNew);
        });
        Event::on(GlobalSet::class, GlobalSet::EVENT_AFTER_DELETE, function ($event) {
            Activity::getRecorder('globalSets')->onDeleted($event->sender);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'globalSet';
    }

    /**
     * @inheritDoc
     */
    protected function loadOldModel(int $id): ?Model
    {
        return GlobalSet::find()->id($id)->one();
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        return ['name', 'handle', 'fieldLayout'];
    }
}