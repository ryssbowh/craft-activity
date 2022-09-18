<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use craft\base\Model;
use craft\db\Query;
use craft\db\Table;
use craft\models\UserGroup;
use craft\services\UserGroups as CraftUserGroups;
use yii\base\Event;

class UserGroups extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(CraftUserGroups::class, CraftUserGroups::EVENT_BEFORE_SAVE_USER_GROUP, function ($event) {
            Activity::getRecorder('userGroups')->beforeSaved($event->userGroup, $event->isNew);
        });
        Event::on(CraftUserGroups::class, CraftUserGroups::EVENT_AFTER_SAVE_USER_GROUP, function ($event) {
            Activity::getRecorder('userGroups')->onSaved($event->userGroup, $event->isNew);
        });
        Event::on(CraftUserGroups::class, CraftUserGroups::EVENT_AFTER_DELETE_USER_GROUP, function ($event) {
            Activity::getRecorder('userGroups')->onDeleted($event->userGroup);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'userGroup';
    }

    /**
     * @inheritDoc
     */
    protected function loadOldModel(int $id): ?Model
    {
        $record = $query = (new Query())
            ->select([
                'id',
                'name',
                'handle',
                'uid',
            ])
            ->from([Table::USERGROUPS])
            ->where(['id' => $id])
            ->one();
        return new UserGroup($record);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        return ['name', 'handle'];
    }
}