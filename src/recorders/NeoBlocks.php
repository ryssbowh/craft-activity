<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use yii\base\Event;

/**
 * @since 1.3.1
 */
class NeoBlocks extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate('neoBlockTypes.{uid}', function (Event $event) {
            Activity::getRecorder('neoBlocks')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd('neoBlockTypes.{uid}', function (Event $event) {
            Activity::getRecorder('neoBlocks')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove('neoBlockTypes.{uid}', function (Event $event) {
            Activity::getRecorder('neoBlocks')->onRemove($event);
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
        return 'neoBlock';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config): array
    {
        return ['name', 'handle', 'childBlocks', 'description', 'enabled', 'fieldLayouts', 'group', 'groupChildBlockTypes', 'ignorePermissions', 'maxBlocks', 'maxChildBlocks', 'maxSiblingBlocks', 'minBlocks', 'minChildBlocks', 'minSiblingBlocks', 'sortOrder', 'topLevel'];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}