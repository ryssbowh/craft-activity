<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ElementsRecorder;
use Ryssbowh\Activity\models\fieldHandlers\elements\Author;
use Ryssbowh\Activity\models\fieldHandlers\elements\Date;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use craft\base\Element;
use craft\elements\Entry;
use craft\services\Elements;
use craft\services\Sections;
use yii\base\Event;

class Entries extends ElementsRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        if (Activity::$plugin->settings->ignoreResave) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_RESAVE_ELEMENT, function (Event $event) {
                Activity::getRecorder('entries')->stopRecording();
            });
        }
        if (Activity::$plugin->settings->ignoreUpdateSlugs) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_UPDATE_SLUG_AND_URI, function (Event $event) {
                Activity::getRecorder('entries')->stopRecording();
            });
        }
        if (Activity::$plugin->settings->ignorePropagate) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_PROPAGATE_ELEMENT, function (Event $event) {
                Activity::getRecorder('entries')->stopRecording();
            });
        }
        //------------Do not record entry changes when sections/entry types are being created/removed
        \Craft::$app->projectConfig->onAdd(Sections::CONFIG_SECTIONS_KEY. '.{uid}', function (Event $event) {
            Activity::getRecorder('entries')->stopRecording();
        });
        \Craft::$app->projectConfig->onUpdate(Sections::CONFIG_SECTIONS_KEY. '.{uid}', function (Event $event) {
            Activity::getRecorder('entries')->stopRecording();
        });
        Event::on(Sections::class, Sections::EVENT_BEFORE_APPLY_ENTRY_TYPE_DELETE, function (Event $event) {
            Activity::getRecorder('entries')->stopRecording();
        });
        Event::on(Sections::class, Sections::EVENT_BEFORE_APPLY_SECTION_DELETE, function (Event $event) {
            Activity::getRecorder('entries')->stopRecording();
        });
        //------------
        Event::on(Entry::class, Entry::EVENT_BEFORE_SAVE, function ($event) {
            Activity::getRecorder('entries')->beforeSaved($event->sender);
        });
        Event::on(Entry::class, Entry::EVENT_AFTER_SAVE, function ($event) {
            Activity::getRecorder('entries')->onSaved($event->sender);
        });
        Event::on(Entry::class, Entry::EVENT_AFTER_DELETE, function ($event) {
            Activity::getRecorder('entries')->onDeleted($event->sender);
        });
        Event::on(Entry::class, Entry::EVENT_AFTER_RESTORE, function ($event) {
            Activity::getRecorder('entries')->onRestored($event->sender);
        });
        Event::on(Entry::class, Entry::EVENT_AFTER_MOVE_IN_STRUCTURE, function ($event) {
            Activity::getRecorder('entries')->onMoved($event->sender);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getElementType(): string
    {
        return Entry::class;
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'entry';
    }

    /**
     * @inheritDoc
     */
    protected function getFieldsValues(Element $entry): array
    {
        $fields = array_merge(
            [
                'slug' => new Plain([
                    'name' => \Craft::t('app', 'Slug'),
                    'value' => $entry->slug
                ]),
                'status' => new Plain([
                    'name' => \Craft::t('app', 'Status'),
                    'value' => Entry::statuses()[$entry->status],
                ]),
                'postDate' => new Date([
                    'name' => \Craft::t('app', 'Post date'),
                    'rawValue' => $entry->postDate,
                ]),
                'expiryDate' => new Date([
                    'name' => \Craft::t('app', 'Expiry date'),
                    'rawValue' => $entry->expiryDate,
                ]),
            ],
            $this->getCustomFieldValues($entry)
        );
        if ($entry->section->type != 'single' and $entry->author) {
            $fields['author'] = new Author([
                'name' => \Craft::t('app', 'Author'),
                'rawValue' => $entry->author
            ]);
        }
        return $fields;
    }
}