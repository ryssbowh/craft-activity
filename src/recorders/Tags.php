<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class Tags extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    protected ?string $deleteTypesCategory = 'tagGroups';

    /**
     * @inheritDoc
     */
    protected array $deleteTypes = ['tagGroupDeleted', 'tagGroupSaved', 'tagGroupCreated'];

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_TAG_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('tags')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_TAG_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('tags')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_TAG_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('tags')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'tagGroup';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['name', 'handle', 'fieldLayouts'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}
