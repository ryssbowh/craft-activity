<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use Ryssbowh\Activity\base\Recorder;
use craft\base\Model;
use craft\db\Query;
use craft\db\Table;
use craft\models\FieldGroup;
use craft\services\Fields;
use yii\base\Event;

class FieldGroups extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(Fields::class, Fields::EVENT_BEFORE_SAVE_FIELD_GROUP, function ($event) {
            Activity::getRecorder('fieldGroups')->beforeSaved($event->group, $event->isNew);
        });
        Event::on(Fields::class, Fields::EVENT_AFTER_SAVE_FIELD_GROUP, function ($event) {
            Activity::getRecorder('fieldGroups')->onSaved($event->group, $event->isNew);
        });
        Event::on(Fields::class, Fields::EVENT_AFTER_DELETE_FIELD_GROUP, function ($event) {
            Activity::getRecorder('fieldGroups')->onDeleted($event->group);
        });
    }
    
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'fieldGroup';
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
                'uid',
            ])
            ->from([Table::FIELDGROUPS])
            ->where(['id' => $id])->one();
        if (!$query) {
            return null;
        }
        return new FieldGroup($query);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        return ['name'];
    }
}