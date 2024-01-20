<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class FieldGroups extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    protected ?string $deleteTypesCategory = 'fieldGroups';

    /**
     * @inheritDoc
     */
    protected array $deleteTypes = ['fieldGroupCreated', 'fieldGroupSaved', 'fieldGroupDeleted'];

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_FIELD_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fieldGroups')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_FIELD_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fieldGroups')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_FIELD_GROUPS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fieldGroups')->onRemove($event);
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
