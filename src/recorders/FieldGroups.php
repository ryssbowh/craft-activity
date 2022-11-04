<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\Fields;
use yii\base\Event;

class FieldGroups extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onUpdate(Fields::CONFIG_FIELDGROUP_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('fieldGroups')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(Fields::CONFIG_FIELDGROUP_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('fieldGroups')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(Fields::CONFIG_FIELDGROUP_KEY . '.{uid}', function (Event $event) {
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
    protected function _getTrackedFieldNames(): array
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