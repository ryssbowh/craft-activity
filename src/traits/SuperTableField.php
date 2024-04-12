<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\models\fieldHandlers\elements\MatrixNew;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\EntryTypes;
use Ryssbowh\Activity\models\logs\fields\SuperTableBlockCreated;
use Ryssbowh\Activity\models\logs\fields\SuperTableBlockDeleted;
use Ryssbowh\Activity\models\logs\fields\SuperTableBlockSaved;
use Ryssbowh\Activity\services\Fields;
use Ryssbowh\Activity\services\Types;
use craft\services\ProjectConfig;
use verbb\supertable\fields\SuperTableField as Field;
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
            $event->labels['verbb\\supertable\\fields\\SuperTableField'] = [
                'settings.entryTypes' => \Craft::t('app', 'Entry Types'),
                'settings.siteSettings' => \Craft::t('app', 'Site Settings'),
                'settings.propagationMethod' => \Craft::t('app', 'Propagation Method'),
                'settings.minEntries' => \Craft::t('app', 'Min Entries'),
                'settings.maxEntries' => \Craft::t('app', 'Max Entries'),
                'settings.viewMode' => \Craft::t('app', 'View Mode'),
                'settings.showCardsInGrid' => \Craft::t('app', 'Show cards in a grid'),
                'settings.createButtonLabel' => \Craft::t('app', '“New” Button Label'),
            ];
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_TRACKED_FIELDS, function (Event $event) {
            $event->tracked['verbb\\supertable\\fields\\SuperTableField'] = ['settings.entryTypes', 'settings.siteSettings', 'settings.propagationMethod', 'settings.minEntries', 'settings.maxEntries', 'settings.viewMode', 'settings.createButtonLabel', 'settings.showCardsInGrid'];
        });
        Event::on(EntryTypes::class, EntryTypes::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[verbb\\supertable\\fields\\SuperTableField].entryTypes';
        });
        Event::on(MatrixNew::class, MatrixNew::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = Field::class;
        });
        //These are registered for legacy events but aren't used anymore
        Event::on(Types::class, Types::EVENT_REGISTER, function (Event $event) {
            $event->add(new SuperTableBlockCreated());
            $event->add(new SuperTableBlockSaved());
            $event->add(new SuperTableBlockDeleted());
        });
    }
}
