<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\models\fieldHandlers\elements\LongText;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\EntryTypes;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Transform;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Transforms;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Volumes;
use Ryssbowh\Activity\services\Fields;
use craft\services\ProjectConfig;
use yii\base\Event;

/**
 * @since 2.4.0
 */
trait CkeditorField
{
    /**
     * Register everything needed for Redactor fields tracking
     */
    protected function initCkeditorField()
    {
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_LABELS, function (Event $event) {
            $categories = array_keys(\Craft::$app->i18n->translations);
            $category = in_array('ckeditor', $categories) ? 'ckeditor' : 'activity';
            $event->labels['craft\\ckeditor\\Field'] = [
                'settings.ckeConfig' => \Craft::t($category, 'CKEditor Config'),
                'settings.wordLimit' => \Craft::t($category, 'Word Limit'),
                'settings.showWordCount' => \Craft::t($category, 'Show word count'),
                'settings.enableSourceEditingForNonAdmins' => \Craft::t($category, 'Show the “Source” button for non-admin users?'),
                'settings.showUnpermittedVolumes' => \Craft::t($category, 'Show unpermitted volumes'),
                'settings.showUnpermittedFiles' => \Craft::t($category, 'Show unpermitted files'),
                'settings.availableVolumes' => \Craft::t($category, 'Available Volumes'),
                'settings.availableTransforms' => \Craft::t($category, 'Available Transforms'),
                'settings.defaultTransform' => \Craft::t($category, 'Default Transform'),
                'settings.purifyHtml' => \Craft::t($category, 'Purify HTML'),
                'settings.purifierConfig' => \Craft::t($category, 'HTML Purifier Config'),
                'settings.columnType' => \Craft::t($category, 'Column Type'),
                'settings.entryTypes' => \Craft::t('app', 'Entry Types'),
            ];
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_TRACKED_FIELDS, function (Event $event) {
            $event->tracked['craft\\ckeditor\\Field'] = ['settings.ckeConfig', 'settings.wordLimit', 'settings.showWordCount', 'settings.enableSourceEditingForNonAdmins', 'settings.showUnpermittedVolumes', 'settings.showUnpermittedFiles', 'settings.availableVolumes', 'settings.availableTransforms', 'settings.defaultTransform', 'settings.purifyHtml', 'settings.purifierConfig', 'settings.columnType', 'settings.entryTypes'];
        });
        Event::on(LongText::class, LongText::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = 'craft\\ckeditor\\Field';
        });
        Event::on(Volumes::class, Volumes::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\ckeditor\\Field].availableVolumes';
        });
        Event::on(EntryTypes::class, EntryTypes::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\ckeditor\\Field].entryTypes';
        });
        Event::on(Transforms::class, Transforms::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\ckeditor\\Field].availableTransforms';
        });
        Event::on(Transform::class, Transform::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\ckeditor\\Field].defaultTransform';
        });
    }
}
