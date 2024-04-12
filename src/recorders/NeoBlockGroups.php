<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use yii\base\Event;

/**
 * @since 2.3.1
 */
class NeoBlockGroups extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate('neo.blockTypeGroups.{uid}', function (Event $event) {
            Activity::getRecorder('neoBlockGroups')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd('neo.blockTypeGroups.{uid}', function (Event $event) {
            Activity::getRecorder('neoBlockGroups')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove('neo.blockTypeGroups.{uid}', function (Event $event) {
            Activity::getRecorder('neoBlockGroups')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    public function modifyParams(array $params, ConfigEvent $event): array
    {
        $uid = $event->newValue['field'] ?? $event->oldValue['field'];
        $field = \Craft::$app->fields->getFieldByUid($uid);
        $params['data'] = [
            'field_uid' => $uid,
            'field_name' => $field->name
        ];
        return $params;
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'neoBlockGroup';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['alwaysShowDropdown', 'name'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}
