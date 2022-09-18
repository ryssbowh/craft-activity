<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use Ryssbowh\Activity\base\Recorder;
use craft\base\Model;
use craft\db\Query;
use craft\db\Table;
use craft\models\Section;
use craft\services\Sections as CraftSections;
use yii\base\Event;

class Sections extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(CraftSections::class, CraftSections::EVENT_BEFORE_SAVE_SECTION, function (Event $event) {
            Activity::getRecorder('sections')->beforeSaved($event->section, $event->isNew);
        });
        Event::on(CraftSections::class, CraftSections::EVENT_AFTER_SAVE_SECTION, function (Event $event) {
            Activity::getRecorder('sections')->onSaved($event->section, $event->isNew);
        });
        Event::on(CraftSections::class, CraftSections::EVENT_AFTER_DELETE_SECTION, function (Event $event) {
            Activity::getRecorder('sections')->onDeleted($event->section);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'section';
    }

    /**
     * @inheritDoc
     */
    protected function loadOldModel(int $id): ?Model
    {
        $joinCondition = [
            'and',
            '[[structures.id]] = [[sections.structureId]]',
            ['structures.dateDeleted' => null],
        ];

        $record = (new Query())
            ->select([
                'sections.id',
                'sections.structureId',
                'sections.name',
                'sections.handle',
                'sections.type',
                'sections.enableVersioning',
                'sections.uid',
                'sections.propagationMethod',
                'sections.previewTargets',
                'sections.defaultPlacement',
                'structures.maxLevels',
            ])
            ->leftJoin(['structures' => Table::STRUCTURES], $joinCondition)
            ->from(['sections' => Table::SECTIONS])
            ->where(['sections.id' => $id])
            ->one();
        if (!$record) {
            return null;
        }
        $section = new Section($record);
        $section->siteSettings = \Craft::$app->sections->getSectionSiteSettings($id);
        return $section;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        $fields = ['name', 'handle', 'type', 'enableVersioning', 'siteSettings', 'previewTargets'];
        if ($model->type == 'channel') {
            $fields[] = 'propagationMethod';
        } elseif ($model->type == 'structure') {
            $fields = array_merge($fields, ['propagationMethod', 'maxLevels', 'defaultPlacement']);
        }
        return $fields;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(): array
    {
        return [
            'enableVersioning' => 'bool'
        ];
    }
}