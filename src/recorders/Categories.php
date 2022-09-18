<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\ElementsRecorder;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use craft\base\Element;
use craft\elements\Category;
use craft\services\Categories as CraftCategories;
use craft\services\Sites;
use yii\base\Event;

class Categories extends ElementsRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(Sites::class, Sites::EVENT_BEFORE_SAVE_SITE, function ($event) {
            Activity::getRecorder('categories')->stopRecording = true;
        });
        Event::on(CraftCategories::class, CraftCategories::EVENT_BEFORE_SAVE_GROUP, function ($event) {
            Activity::getRecorder('categories')->stopRecording = true;
        });
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
    protected function getFields(Element $category): array
    {
        return array_merge(
            [
                'slug' => new Plain([
                    'label' => \Craft::t('app', 'Slug'),
                    'value' => $category->slug
                ]),
                'status' => new Plain([
                    'label' => \Craft::t('app', 'Status'),
                    'value' => Category::statuses()[$category->status],
                ])
            ],
            $this->getFieldValues($category)
        );
    }
}