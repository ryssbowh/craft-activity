<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\models\fieldHandlers\elements\SuperTable;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\BlockFields;
use Ryssbowh\Activity\models\logs\fields\SuperTableBlockCreated;
use Ryssbowh\Activity\models\logs\fields\SuperTableBlockDeleted;
use Ryssbowh\Activity\models\logs\fields\SuperTableBlockSaved;
use Ryssbowh\Activity\recorders\SuperTableBlocks;
use Ryssbowh\Activity\services\FieldHandlers;
use Ryssbowh\Activity\services\Fields;
use Ryssbowh\Activity\services\Recorders;
use Ryssbowh\Activity\services\Types;
use yii\base\Event;

/**
 * @since 2.2.0
 */
trait SuperTableField
{
    /**
     * Register everything needed for Super table fields tracking
     */
    protected function initSuperTableField()
    {
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_LABELS, function (Event $event) {
            $categories = array_keys(\Craft::$app->i18n->translations);
            $category = in_array('super-table', $categories) ? 'super-table' : 'activity';
            $event->labels['verbb\\supertable\\fields\\SuperTableField'] = [
                'settings.fieldLayout' => \Craft::t($category, 'Field Layout'),
                'settings.maxRows' => \Craft::t($category, 'Max Rows'),
                'settings.minRows' => \Craft::t($category, 'Min Rows'),
                'settings.selectionLabel' => \Craft::t($category, 'New Row Label'),
                'settings.staticField' => \Craft::t($category, 'Static field')
            ];
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_TRACKED_FIELDS, function (Event $event) {
            $event->tracked['verbb\\supertable\\fields\\SuperTableField'] = ['settings.fieldLayout', 'settings.maxRows', 'settings.minRows', 'settings.selectionLabel', 'settings.staticField'];
        });
        Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_ELEMENT_HANDLERS, function (Event $event) {
            $event->add(SuperTable::class);
        });
        Event::on(BlockFields::class, BlockFields::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = 'superTableBlockTypes.{uid}.fields';
        });
        Event::on(Recorders::class, Recorders::EVENT_REGISTER, function (Event $event) {
            $event->add('superTableBlocks', new SuperTableBlocks);
        });
        Event::on(Types::class, Types::EVENT_REGISTER, function (Event $event) {
            $event->add(new SuperTableBlockCreated);
            $event->add(new SuperTableBlockSaved);
            $event->add(new SuperTableBlockDeleted);
        });
    }
}