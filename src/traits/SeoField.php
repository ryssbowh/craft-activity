<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\models\fieldHandlers\elements\Seo;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\SeoRobots;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\SeoTitle;
use Ryssbowh\Activity\services\FieldHandlers;
use Ryssbowh\Activity\services\Fields;
use yii\base\Event;

/**
 * @since 1.2.0
 */
trait SeoField
{
    /**
     * Register everything needed for SEO fields tracking
     */
    protected function initSeoField()
    {
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_LABELS, function (Event $event) {
            $event->labels['ether\\seo\\fields\\SeoField'] = [
                'settings.description' => \Craft::t('app', 'Description'),
                'settings.hideSocial' => \Craft::t('activity', 'Hide Social Meta Tab'),
                'settings.robots' => \Craft::t('app', 'Robots'),
                'settings.socialImage' => \Craft::t('app', 'Social Image'),
                'settings.title' => \Craft::t('app', 'Page Title'),
            ];
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_TRACKED_FIELDS, function (Event $event) {
            $event->tracked['ether\\seo\\fields\\SeoField'] = ['settings.description', 'settings.hideSocial', 'settings.robots', 'settings.socialImage', 'settings.title'];
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPINGS, function (Event $event) {
            $event->typings['ether\\seo\\fields\\SeoField'] = [
                'settings.hideSocial' => 'bool'
            ];
        });
        Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_ELEMENT_HANDLERS, function (Event $event) {
            $event->add(Seo::class);
        });
        Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_PROJECTCONFIG_HANDLERS, function (Event $event) {
            $event->add(SeoTitle::class);
            $event->add(SeoRobots::class);
        });
    }
}