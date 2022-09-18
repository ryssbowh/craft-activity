<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use Ryssbowh\Activity\base\Recorder;
use craft\base\Model;
use craft\db\Query;
use craft\db\Table;
use craft\models\SiteGroup;
use craft\services\Sites;
use yii\base\Event;

class SiteGroups extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(Sites::class, Sites::EVENT_BEFORE_SAVE_SITE_GROUP, function ($event) {
            Activity::getRecorder('siteGroups')->beforeSaved($event->group, $event->isNew);
        });
        Event::on(Sites::class, Sites::EVENT_AFTER_SAVE_SITE_GROUP, function ($event) {
            Activity::getRecorder('siteGroups')->onSaved($event->group, $event->isNew);
        });
        Event::on(Sites::class, Sites::EVENT_AFTER_DELETE_SITE_GROUP, function ($event) {
            Activity::getRecorder('siteGroups')->onDeleted($event->group);
        });
    }
    
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'siteGroup';
    }

    /**
     * @inheritDoc
     */
    protected function loadOldModel(int $id): ?Model
    {
        $record = (new Query())
            ->select([
                'id',
                'name',
                'uid',
            ])
            ->from([Table::SITEGROUPS])
            ->where(['id' => $id])
            ->one();
        return new SiteGroup($record);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        return ['name'];
    }
}