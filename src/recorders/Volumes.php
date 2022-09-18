<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use Ryssbowh\Activity\base\Recorder;
use craft\base\Model;
use craft\services\Volumes as CraftVolumes;
use craft\volumes\Local;
use yii\base\Event;

class Volumes extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(CraftVolumes::class, CraftVolumes::EVENT_BEFORE_SAVE_VOLUME, function ($event) {
            Activity::getRecorder('volumes')->beforeSaved($event->volume, $event->isNew);
        });
        Event::on(CraftVolumes::class, CraftVolumes::EVENT_AFTER_SAVE_VOLUME, function ($event) {
            Activity::getRecorder('volumes')->onSaved($event->volume, $event->isNew);
        });
        Event::on(CraftVolumes::class, CraftVolumes::EVENT_AFTER_DELETE_VOLUME, function ($event) {
            Activity::getRecorder('volumes')->onDeleted($event->volume);
        });
    }
    
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'volume';
    }

    /**
     * @inheritDoc
     */
    protected function loadOldModel(int $id): ?Model
    {
        return \Craft::$app->volumes->getVolumeById($id);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        $names = ['name', 'handle', 'hasUrls', 'type', 'titleTranslationMethod', 'fieldLayout', 'titleTranslationKeyFormat'];
        if ($model instanceof Local) {
            $names[] = 'path';
        }
        return $names;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(): array
    {
        return [
            'hasUrls' => 'bool'
        ];
    }
}