<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ElementsRecorder;
use Ryssbowh\Activity\models\fieldHandlers\elements\Authors;
use Ryssbowh\Activity\models\fieldHandlers\elements\Date;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use craft\base\Element;
use craft\elements\Entry;
use craft\services\Elements;
use craft\services\Entries as CraftEntries;
use craft\services\ProjectConfig;
use craft\services\Revisions;
use yii\base\Event;

class Entries extends ElementsRecorder
{
    /**
     * @inheritDoc
     */
    protected ?string $deleteTypesCategory = 'entries';

    /**
     * @inheritDoc
     */
    protected array $deleteTypes = ['entryDeleted', 'entryCreated', 'entryMoved', 'entryRestored', 'entryReverted', 'entrySaved'];

    /**
     * @inheritDoc
     */
    public function init(): void
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
        //------------Empty log queue after entry types have been created/removed
        Event::on(CraftEntries::class, CraftEntries::EVENT_AFTER_SAVE_ENTRY_TYPE, function (Event $event) {
            Activity::getRecorder('entries')->emptyQueue();
        });
        Event::on(CraftEntries::class, CraftEntries::EVENT_AFTER_DELETE_ENTRY_TYPE, function (Event $event) {
            Activity::getRecorder('entries')->emptyQueue();
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
        Event::on(Revisions::class, Revisions::EVENT_BEFORE_REVERT_TO_REVISION, function (Event $event) {
            if ($event->canonical instanceof Entry) {
                Activity::getRecorder('entries')->beforeReverted($event->revision, $event->canonical);
            }
        });
        Event::on(Revisions::class, Revisions::EVENT_AFTER_REVERT_TO_REVISION, function (Event $event) {
            if ($event->canonical instanceof Entry) {
                Activity::getRecorder('entries')->emptyQueue();
                Activity::getRecorder('entries')->onReverted($event->canonical, $event->revisionNum);
            }
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
        $statuses = Entry::statuses();
        $statuses['enabled'] = \Craft::t('app', 'Enabled');
        $statuses['draft'] = \Craft::t('app', 'Draft');
        $fields = array_merge(
            [
                'slug' => new Plain([
                    'name' => \Craft::t('app', 'Slug'),
                    'value' => $entry->slug
                ]),
                'status' => new Plain([
                    'name' => \Craft::t('app', 'Status'),
                    'value' => $statuses[$entry->status],
                ]),
                'postDate' => new Date([
                    'name' => \Craft::t('app', 'Post date'),
                    'rawValue' => $entry->postDate,
                ]),
                'expiryDate' => new Date([
                    'name' => \Craft::t('app', 'Expiry date'),
                    'rawValue' => $entry->expiryDate,
                ]),
                'authors' => new Authors([
                    'name' => \Craft::t('app', 'Authors'),
                    'rawValue' => $entry->authors
                ])
            ],
            $this->getCustomFieldValues($entry)
        );
        return $fields;
    }
}
