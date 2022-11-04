<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\models\fieldHandlers\elements\LongText;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Transform;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Transforms;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Volumes;
use Ryssbowh\Activity\services\Fields;
use craft\services\ProjectConfig;
use yii\base\Event;

trait RedactorField
{
    protected function initRedactorField()
    {
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_LABELS, function (Event $event) {
            $categories = array_keys(\Craft::$app->i18n->translations);
            $category = in_array('redactor', $categories) ? 'redactor' : 'activity';
            $event->labels['craft\\redactor\\Field'] = [
                'settings.uiMode' => \Craft::t($category, 'UI Mode'),
                'settings.manualConfig' => \Craft::t('activity', 'Custom Config'),
                'settings.redactorConfig' => \Craft::t($category, 'Redactor Config'),
                'settings.showHtmlButtonForNonAdmins' => \Craft::t($category, 'Show the “HTML” button for non-admin users'),
                'settings.showUnpermittedVolumes' => \Craft::t($category, 'Show unpermitted volumes'),
                'settings.showUnpermittedFiles' => \Craft::t($category, 'Show unpermitted files'),
                'settings.availableVolumes' => \Craft::t($category, 'Available Volumes'),
                'settings.availableTransforms' => \Craft::t($category, 'Available Transforms'),
                'settings.defaultTransform' => \Craft::t($category, 'Default Transform'),
                'settings.removeEmptyTags' => \Craft::t($category, 'Remove empty tags'),
                'settings.removeInlineStyles' => \Craft::t($category, 'Remove inline styles'),
                'settings.removeNbsp' => \Craft::t($category, 'Replace non-breaking spaces with regular spaces'),
                'settings.purifyHtml' => \Craft::t($category, 'Purify HTML'),
                'settings.purifierConfig' => \Craft::t($category, 'HTML Purifier Config'),
                'settings.columnType' => \Craft::t($category, 'Column Type'),
            ];
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_TRACKED_FIELDS, function (Event $event) {
            $event->tracked['craft\\redactor\\Field'] = ['settings.uiMode', 'settings.manualConfig', 'settings.redactorConfig', 'settings.showHtmlButtonForNonAdmins', 'settings.showUnpermittedVolumes', 'settings.showUnpermittedFiles', 'settings.availableVolumes', 'settings.availableTransforms', 'settings.removeEmptyTags', 'settings.removeInlineStyles', 'settings.removeNbsp', 'settings.purifyHtml', 'settings.purifierConfig', 'settings.columnType'];
        });
        Event::on(LongText::class, LongText::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = 'craft\\redactor\\Field';
        });
        Event::on(Volumes::class, Volumes::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\redactor\\Field].availableVolumes';
        });
        Event::on(Transforms::class, Transforms::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\redactor\\Field].availableTransforms';
        });
        Event::on(Transform::class, Transform::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\redactor\\Field].defaultTransform';
        });
    }
}