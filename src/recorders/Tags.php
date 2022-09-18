<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use craft\base\Model;
use craft\models\TagGroup;
use craft\records\TagGroup as TagGroupRecord;
use craft\services\Tags as CraftTags;
use yii\base\Event;

class Tags extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(CraftTags::class, CraftTags::EVENT_BEFORE_SAVE_GROUP, function ($event) {
            Activity::getRecorder('tags')->beforeSaved($event->tagGroup, $event->isNew);
        });
        Event::on(CraftTags::class, CraftTags::EVENT_AFTER_SAVE_GROUP, function ($event) {
            Activity::getRecorder('tags')->onSaved($event->tagGroup, $event->isNew);
        });
        Event::on(CraftTags::class, CraftTags::EVENT_AFTER_DELETE_GROUP, function ($event) {
            Activity::getRecorder('tags')->onDeleted($event->tagGroup);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'tagGroup';
    }

    /**
     * @inheritDoc
     */
    protected function loadOldModel(int $id): ?Model
    {
        $record = TagGroupRecord::find()
            ->where(['id' => $id])
            ->one();
        if (!$record) {
            return null;
        }

        return new TagGroup($record->toArray([
            'id',
            'name',
            'handle',
            'fieldLayoutId',
            'uid',
        ]));
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        return ['name', 'handle', 'fieldLayout'];
    }
}