<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class SiteGroups extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    protected ?string $deleteTypesCategory = 'siteGroups';

    /**
     * @inheritDoc
     */
    protected array $deleteTypes = ['siteGroupDeleted', 'siteGroupSaved', 'siteGroupCreated'];

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_SITE_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('siteGroups')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_SITE_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('siteGroups')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_SITE_GROUPS . '.{uid}', function (Event $event) {
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
