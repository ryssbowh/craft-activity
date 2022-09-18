<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use craft\base\Model;
use craft\models\CategoryGroup;
use craft\records\CategoryGroup as CategoryGroupRecord;
use craft\services\Categories;
use yii\base\Event;

class CategoryGroups extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(Categories::class, Categories::EVENT_BEFORE_SAVE_GROUP, function ($event) {
            Activity::getRecorder('categoryGroups')->beforeSaved($event->categoryGroup, $event->isNew);
        });
        Event::on(Categories::class, Categories::EVENT_AFTER_SAVE_GROUP, function ($event) {
            Activity::getRecorder('categoryGroups')->onSaved($event->categoryGroup, $event->isNew);
        });
        Event::on(Categories::class, Categories::EVENT_AFTER_DELETE_GROUP, function ($event) {
            Activity::getRecorder('categoryGroups')->onDeleted($event->categoryGroup);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'categoryGroup';
    }

    /**
     * @inheritDoc
     */
    protected function loadOldModel(int $id): ?Model
    {
        $record = CategoryGroupRecord::find()
                ->with('structure')
                ->where(['id' => $id])
                ->one();
        if (!$record) {
            return null;
        }
        $group = new CategoryGroup($record->toArray([
            'id',
            'structureId',
            'fieldLayoutId',
            'name',
            'handle',
            'defaultPlacement',
            'uid',
        ]));

        if ($record->structure) {
            $group->maxLevels = $record->structure->maxLevels;
        }

        $group->siteSettings = \Craft::$app->categories->getGroupSiteSettings($id);

        return $group;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        return ['name', 'handle', 'maxLevels', 'defaultPlacement', 'siteSettings', 'fieldLayout'];
    }
}