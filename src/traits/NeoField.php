<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\models\fieldHandlers\elements\Neo;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\FieldLayout;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\NeoGroup;
use Ryssbowh\Activity\models\logs\fields\NeoBlockCreated;
use Ryssbowh\Activity\models\logs\fields\NeoBlockDeleted;
use Ryssbowh\Activity\models\logs\fields\NeoBlockGroupCreated;
use Ryssbowh\Activity\models\logs\fields\NeoBlockGroupDeleted;
use Ryssbowh\Activity\models\logs\fields\NeoBlockGroupSaved;
use Ryssbowh\Activity\models\logs\fields\NeoBlockSaved;
use Ryssbowh\Activity\recorders\NeoBlockGroups;
use Ryssbowh\Activity\recorders\NeoBlocks;
use Ryssbowh\Activity\services\FieldHandlers;
use Ryssbowh\Activity\services\Fields;
use Ryssbowh\Activity\services\Recorders;
use Ryssbowh\Activity\services\Types;
use yii\base\Event;

/**
 * @since 2.3.1
 */
trait NeoField
{
    /**
     * Register everything needed for Neo fields tracking
     */
    protected function initNeoField()
    {
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_LABELS, function (Event $event) {
            $categories = array_keys(\Craft::$app->i18n->translations);
            $category = in_array('neo', $categories) ? 'neo' : 'activity';
            $event->labels['benf\\neo\\Field'] = [
                'settings.minBlocks' => \Craft::t($category, 'Min Blocks'),
                'settings.maxBlocks' => \Craft::t($category, 'Max Blocks'),
                'settings.minTopBlocks' => \Craft::t($category, 'Min Top-Level Blocks'),
                'settings.maxTopBlocks' => \Craft::t($category, 'Max Top-Level Blocks'),
                'settings.minLevels' => \Craft::t($category, 'Min Levels'),
                'settings.maxLevels' => \Craft::t($category, 'Max Levels')
            ];
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_TRACKED_FIELDS, function (Event $event) {
            $event->tracked['benf\\neo\\Field'] = ['settings.minBlocks', 'settings.maxBlocks', 'settings.minTopBlocks', 'settings.maxTopBlocks', 'settings.minLevels', 'settings.maxLevels'];
        });
        Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_ELEMENT_HANDLERS, function (Event $event) {
            $event->add(Neo::class);
        });
        Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_PROJECTCONFIG_HANDLERS, function (Event $event) {
            $event->add(NeoGroup::class);
        });
        Event::on(FieldLayout::class, FieldLayout::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = 'neoBlockTypes.{uid}.fieldLayouts';
        });
        Event::on(Recorders::class, Recorders::EVENT_REGISTER, function (Event $event) {
            $event->add('neoBlocks', new NeoBlocks);
            $event->add('neoBlockGroups', new NeoBlockGroups);
        });
        Event::on(Types::class, Types::EVENT_REGISTER, function (Event $event) {
            $event->add(new NeoBlockCreated);
            $event->add(new NeoBlockSaved);
            $event->add(new NeoBlockDeleted);
            $event->add(new NeoBlockGroupCreated);
            $event->add(new NeoBlockGroupSaved);
            $event->add(new NeoBlockGroupDeleted);
        });
    }
}