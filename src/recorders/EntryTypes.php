<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use Ryssbowh\Activity\base\Recorder;
use craft\base\Model;
use craft\db\Query;
use craft\db\Table;
use craft\models\EntryType;
use craft\services\Sections;
use yii\base\Event;

class EntryTypes extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(Sections::class, Sections::EVENT_BEFORE_SAVE_ENTRY_TYPE, function (Event $event) {
            Activity::getRecorder('entryTypes')->beforeSaved($event->entryType, $event->isNew);
        });
        Event::on(Sections::class, Sections::EVENT_AFTER_SAVE_ENTRY_TYPE, function (Event $event) {
            Activity::getRecorder('entryTypes')->onSaved($event->entryType, $event->isNew);
        });
        Event::on(Sections::class, Sections::EVENT_AFTER_DELETE_ENTRY_TYPE, function (Event $event) {
            Activity::getRecorder('entryTypes')->onDeleted($event->entryType);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'entryType';
    }

    /**
     * @inheritDoc
     */
    protected function modifyParams(array $params, string $type, Model $model)
    {
        $params['section'] = $model->section;
        return $params;
    }

    /**
     * @inheritDoc
     */
    protected function loadOldModel(int $id): ?Model
    {
        $query = (new Query())
            ->select([
                'id',
                'sectionId',
                'fieldLayoutId',
                'name',
                'handle',
                'sortOrder',
                'hasTitleField',
                'titleFormat',
                'uid',
                'titleTranslationMethod',
                'titleTranslationKeyFormat'
            ])
            ->from([Table::ENTRYTYPES])
            ->where(['id' => $id])
            ->one();
        if (!$query) {
            return null;
        }
        return new EntryType($query);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        return ['name', 'handle', 'hasTitleField', 'titleTranslationMethod', 'titleFormat', 'fieldLayout'];
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(): array
    {
        return [
            'hasTitleField' => 'bool'
        ];
    }
}