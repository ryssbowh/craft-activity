<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use Ryssbowh\Activity\base\Recorder;
use craft\base\Model;
use craft\db\Query;
use craft\db\Table;
use craft\services\Fields as CraftFields;
use yii\base\Event;

class Fields extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(CraftFields::class, CraftFields::EVENT_BEFORE_SAVE_FIELD, function ($event) {
            Activity::getRecorder('fields')->beforeSaved($event->field, $event->isNew);
        });
        Event::on(CraftFields::class, CraftFields::EVENT_AFTER_SAVE_FIELD, function ($event) {
            Activity::getRecorder('fields')->onSaved($event->field, $event->isNew);
        });
        Event::on(CraftFields::class, CraftFields::EVENT_AFTER_DELETE_FIELD, function ($event) {
            Activity::getRecorder('fields')->onDeleted($event->field);
        });
    }
        
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'field';
    }

    /**
     * @inheritDoc
     */
    protected function loadOldModel(int $id): ?Model
    {
        $query = (new Query())
            ->select([
                'fields.id',
                'fields.dateCreated',
                'fields.dateUpdated',
                'fields.groupId',
                'fields.name',
                'fields.handle',
                'fields.context',
                'fields.instructions',
                'fields.translationMethod',
                'fields.translationKeyFormat',
                'fields.type',
                'fields.settings',
                'fields.uid',
                'fields.searchable',
                'fields.columnSuffix',
            ])
            ->from(['fields' => Table::FIELDS])
            ->where(['id' => $id])
            ->one();
        if (!$query) {
            return null;
        }
        return \Craft::$app->fields->createField($query);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        return ['name', 'type', 'handle', 'group.name', 'instructions', 'searchable', 'translationMethod'];
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(): array
    {
        return [
            'searchable' => 'bool'
        ];
    }
}