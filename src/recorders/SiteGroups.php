<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\Sites;
use yii\base\Event;

class SiteGroups extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(Sites::CONFIG_SITEGROUP_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('siteGroups')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(Sites::CONFIG_SITEGROUP_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('siteGroups')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(Sites::CONFIG_SITEGROUP_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('siteGroups')->onRemove($event);
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
    protected function getTrackedFieldNames(array $config): array
    {
        return ['name'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}