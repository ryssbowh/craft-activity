<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ElementsRecorder;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use craft\base\Element;
use craft\elements\Category;
use craft\services\Elements;
use yii\base\Event;

class Categories extends ElementsRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        if (Activity::$plugin->settings->ignoreResave) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_RESAVE_ELEMENT, function (Event $event) {
                Activity::getRecorder('categories')->stopRecording();
            });
        }
        if (Activity::$plugin->settings->ignoreUpdateSlugs) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_UPDATE_SLUG_AND_URI, function (Event $event) {
                Activity::getRecorder('categories')->stopRecording();
            });
        }
        if (Activity::$plugin->settings->ignorePropagate) {
            Event::on(Elements::class, Elements::EVENT_BEFORE_PROPAGATE_ELEMENT, function (Event $event) {
                Activity::getRecorder('categories')->stopRecording();
            });
        }
        Event::on(Category::class, Category::EVENT_BEFORE_SAVE, function ($event) {
            Activity::getRecorder('categories')->beforeSaved($event->sender);
        });
        Event::on(Category::class, Category::EVENT_AFTER_SAVE, function ($event) {
            Activity::getRecorder('categories')->onSaved($event->sender);
        });
        Event::on(Category::class, Category::EVENT_AFTER_DELETE, function ($event) {
            Activity::getRecorder('categories')->onDeleted($event->sender);
        });
        Event::on(Category::class, Category::EVENT_AFTER_RESTORE, function ($event) {
            Activity::getRecorder('categories')->onRestored($event->sender);
        });
        Event::on(Category::class, Category::EVENT_AFTER_MOVE_IN_STRUCTURE, function ($event) {
            Activity::getRecorder('categories')->onMoved($event->sender);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getElementType(): string
    {
        return Category::class;
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'category';
    }

    /**
     * @inheritDoc
     */
    protected function getFieldsValues(Element $category): array
    {
        return array_merge(
            [
                'slug' => new Plain([
                    'name' => \Craft::t('app', 'Slug'),
                    'value' => $category->slug
                ]),
                'status' => new Plain([
                    'name' => \Craft::t('app', 'Status'),
                    'value' => Category::statuses()[$category->status],
                ])
            ],
            $this->getCustomFieldValues($category)
        );
    }
}