<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\models\fieldHandlers\elements\LongText;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Transform;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Transforms;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Volumes;
use Ryssbowh\Activity\services\Fields;
use craft\services\ProjectConfig;
use yii\base\Event;

/**
 * @since 1.2.0
 */
trait TinyMceField
{
    protected function initTinyMceField()
    {
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_LABELS, function (Event $event) {
            $categories = array_keys(\Craft::$app->i18n->translations);
            $category = in_array('tinymce', $categories) ? 'tinymce' : 'activity';
            $event->labels['spicyweb\\tinymce\\fields\\TinyMCE'] = [
                'settings.availableTransforms' => \Craft::t($category, 'Available Transforms'),
                'settings.availableVolumes' => \Craft::t('activity', 'Available Volumes'),
                'settings.columnType' => \Craft::t($category, 'Column Type'),
                'settings.defaultTransform' => \Craft::t($category, 'Default Transform'),
                'settings.purifierConfig' => \Craft::t($category, 'HTML Purifier Config'),
                'settings.purifyHtml' => \Craft::t($category, 'Purify HTML'),
                'settings.removeEmptyTags' => \Craft::t($category, 'Remove empty tags '),
                'settings.removeInlineStyles' => \Craft::t($category, 'Remove inline styles'),
                'settings.removeNbsp' => \Craft::t($category, 'Replace non-breaking spaces with regular spaces'),
                'settings.tinymceConfig' => \Craft::t($category, 'TinyMCE Config'),
            ];
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_TRACKED_FIELDS, function (Event $event) {
            $event->tracked['spicyweb\\tinymce\\fields\\TinyMCE'] = ['settings.availableTransforms', 'settings.availableVolumes', 'settings.columnType', 'settings.defaultTransform', 'settings.purifierConfig', 'settings.purifyHtml', 'settings.removeEmptyTags', 'settings.removeInlineStyles', 'settings.removeNbsp', 'settings.tinymceConfig'];
        });
        Event::on(LongText::class, LongText::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = 'spicyweb\\tinymce\\fields\\TinyMCE';
        });
        Event::on(Volumes::class, Volumes::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[spicyweb\\tinymce\\fields\\TinyMCE].availableVolumes';
        });
        Event::on(Transforms::class, Transforms::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[spicyweb\\tinymce\\fields\\TinyMCE].availableTransforms';
        });
        Event::on(Transform::class, Transform::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[spicyweb\\tinymce\\fields\\TinyMCE].defaultTransform';
        });
    }
}