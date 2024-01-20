<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\models\fieldHandlers\elements\Vizy;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\FieldLayout;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Transform;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Transforms;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\VizyBlockConfig;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Volume;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Volumes;
use Ryssbowh\Activity\services\FieldHandlers;
use Ryssbowh\Activity\services\Fields;
use Ryssbowh\Activity\services\Recorders;
use Ryssbowh\Activity\services\Types;
use craft\services\ProjectConfig;
use yii\base\Event;

/**
 * @since 2.4.0
 */
trait VizyField
{
    /**
     * Register everything needed for Vizy fields tracking
     */
    protected function initVizyField()
    {
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_LABELS, function (Event $event) {
            $categories = array_keys(\Craft::$app->i18n->translations);
            $category = in_array('vizy', $categories) ? 'vizy' : 'activity';
            $event->labels['verbb\\vizy\\fields\\VizyField'] = [
                'settings.availableTransforms' => \Craft::t($category, 'Available Transforms'),
                'settings.availableVolumes' => \Craft::t($category, 'Available Volumes'),
                'settings.blockTypeBehaviour' => \Craft::t($category, 'Block Type Picker Behaviour'),
                'settings.columnType' => \Craft::t($category, 'Column Type'),
                'settings.configSelectionMode' => \Craft::t('activity', 'Editor Config (type)'),
                'settings.defaultTransform' => \Craft::t($category, 'Default Transform'),
                'settings.defaultUploadLocationSource' => \Craft::t('app', 'Default Upload Location'),
                'settings.editorMode' => \Craft::t($category, 'Editor Mode'),
                'settings.initialRows' => \Craft::t($category, 'Initial Rows'),
                'settings.manualConfig' => \Craft::t($category, 'Custom Editor Config'),
                'settings.maxBlocks' => \Craft::t($category, 'Max Blocks'),
                'settings.minBlocks' => \Craft::t($category, 'Min Blocks'),
                'settings.pasteAsPlainText' => \Craft::t($category, 'Plain Text Paste'),
                'settings.showUnpermittedFiles' => \Craft::t('app', 'Show unpermitted files'),
                'settings.showUnpermittedVolumes' => \Craft::t('app', 'Show unpermitted volumes'),
                'settings.trimEmptyParagraphs' => \Craft::t($category, 'Remove Empty Paragraphs'),
                'settings.vizyConfig' => \Craft::t($category, 'Editor Config'),
                'settings.fieldData' => \Craft::t($category, 'Block Configuration'),
            ];
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_TRACKED_FIELDS, function (Event $event) {
            $event->tracked['verbb\\vizy\\fields\\VizyField'] = ['settings.availableTransforms', 'settings.availableVolumes', 'settings.blockTypeBehaviour', 'settings.columnType', 'settings.configSelectionMode', 'settings.defaultTransform', 'settings.defaultUploadLocationSource', 'settings.editorMode', 'settings.initialRows', 'settings.manualConfig', 'settings.maxBlocks', 'settings.minBlocks', 'settings.pasteAsPlainText', 'settings.showUnpermittedFiles', 'settings.showUnpermittedVolumes', 'settings.trimEmptyParagraphs', 'settings.vizyConfig', 'settings.fieldData'];
        });
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPINGS, function (Event $event) {
            $event->typings['verbb\\vizy\\fields\\VizyField'] = [
                'settings.showUnpermittedFiles' => 'bool',
                'settings.showUnpermittedVolumes' => 'bool',
                'settings.trimEmptyParagraphs' => 'bool',
                'settings.pasteAsPlainText' => 'bool',
            ];
        });
        Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_ELEMENT_HANDLERS, function (Event $event) {
            $event->add(Vizy::class);
        });
        Event::on(FieldHandlers::class, FieldHandlers::EVENT_REGISTER_PROJECTCONFIG_HANDLERS, function (Event $event) {
            $event->add(VizyBlockConfig::class);
        });
        Event::on(Volumes::class, Volumes::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[verbb\\vizy\\fields\\VizyField].availableVolumes';
        });
        Event::on(Volume::class, Volume::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[verbb\\vizy\\fields\\VizyField].defaultUploadLocationSource';
        });
        Event::on(Transform::class, Transform::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[verbb\\vizy\\fields\\VizyField].defaultTransform';
        });
        Event::on(Transforms::class, Transforms::EVENT_REGISTER_TARGETS, function (Event $event) {
            $event->targets[] = ProjectConfig::PATH_FIELDS . '.{uid}.settings[verbb\\vizy\\fields\\VizyField].availableTransforms';
        });
    }
}
