<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ConfigModelRecorder;
use Ryssbowh\Activity\base\Recorder;
use craft\base\Model;
use craft\db\Query;
use craft\db\Table;
use craft\models\Site;
use craft\services\Sites as CraftSites;
use yii\base\Event;

class Sites extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(CraftSites::class, CraftSites::EVENT_BEFORE_SAVE_SITE, function ($event) {
            Activity::getRecorder('sites')->beforeSaved($event->site, $event->isNew);
        });
        Event::on(CraftSites::class, CraftSites::EVENT_AFTER_SAVE_SITE, function ($event) {
            if (isset($this->oldFields[get_class($event->site)][$event->isNew ? 0 : $event->site->id])) {
                //This avoids creating a log when a site is made as primary.
                //The currently primary site would also be saved
                Activity::getRecorder('sites')->onSaved($event->site, $event->isNew);
            }
        });
        Event::on(CraftSites::class, CraftSites::EVENT_AFTER_DELETE_SITE, function ($event) {
            Activity::getRecorder('sites')->onDeleted($event->site);
        });
    }
    
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'site';
    }

    /**
     * @inheritDoc
     */
    protected function loadOldModel(int $id): ?Model
    {
        $results = (new Query())
            ->select([
                's.id',
                's.name',
                's.handle',
                's.language',
                's.primary',
                's.hasUrls',
                's.baseUrl',
                's.sortOrder',
                's.uid',
                's.dateCreated',
                's.dateUpdated',
                's.enabled',
                's.groupId',
            ])
            ->where(['s.id' => $id])
            ->from(['s' => Table::SITES])
            ->innerJoin(['sg' => Table::SITEGROUPS], '[[sg.id]] = [[s.groupId]]')
            ->one();
        if (!empty($results)) {
            return new Site($results);
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(Model $model): array
    {
        return ['name', 'handle', 'language', 'primary', 'hasUrls', 'baseUrl', 'enabled', 'group.name'];
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(): array
    {
        return [
            'hasUrls' => 'bool',
            'enabled' => 'bool',
            'primary' => 'bool'
        ];
    }
}