<?php

namespace Ryssbowh\Activity\events;

use craft\fields\Assets;
use craft\fields\Categories;
use craft\fields\Checkboxes;
use craft\fields\Color;
use craft\fields\Date;
use craft\fields\Dropdown;
use craft\fields\Email;
use craft\fields\Entries;
use craft\fields\Lightswitch;
use craft\fields\Matrix;
use craft\fields\MultiSelect;
use craft\fields\Number;
use craft\fields\PlainText;
use craft\fields\RadioButtons;
use craft\fields\Table;
use craft\fields\Tags;
use craft\fields\Time;
use craft\fields\Url;
use craft\fields\Users;
use yii\base\Event;

/**
 * @since 1.2.0
 */
class RegisterFieldLabelsEvent extends Event
{
    /**
     * @var array
     */
    public $labels;

    public function init()
    {
        $labels = [
            '_base' => [
                'name' => \Craft::t('app', 'Name'),
                'fieldGroup' => \Craft::t('app', 'Group'),
                'handle' => \Craft::t('app', 'Handle'),
                'group' => \Craft::t('app', 'Group'),
                'searchable' => \Craft::t('app', 'Use this field’s values as search keywords'),
                'instructions' => \Craft::t('app', 'Default Instructions'),
                'type' => \Craft::t('app', 'Field Type'),
                'translationMethod' => \Craft::t('app', 'Translation Method'),
                'translationKeyFormat' => \Craft::t('app', 'Translation Key Format'),
            ]
        ];
        $labels[Assets::class] = [
            'settings.restrictLocation' => \Craft::t('app', 'Restrict assets to a single location'),
            'settings.restrictedLocationSource' => \Craft::t('activity', 'Asset Location Volume'),
            'settings.restrictedLocationSubpath' => \Craft::t('activity', 'Asset Location Subpath'),
            'settings.allowSubfolders' => \Craft::t('app', 'Allow subfolders'),
            'settings.restrictedDefaultUploadSubpath' => \Craft::t('app', 'Default Upload Location'),
            'settings.sources' => \Craft::t('app', 'Sources'),
            'settings.defaultUploadLocationSource' => \Craft::t('activity', 'Default Upload Location Volume'),
            'settings.defaultUploadLocationSubpath' => \Craft::t('activity', 'Default Upload Location Subpath'),
            'settings.showUnpermittedVolumes' => \Craft::t('app', 'Show unpermitted volumes'),
            'settings.showUnpermittedFiles' => \Craft::t('app', 'Show unpermitted files'),
            'settings.restrictFiles' => \Craft::t('app', 'Restrict allowed file types'),
            'settings.allowUploads' => \Craft::t('app', 'Allow uploading directly to the field'),
            'settings.maxRelations' => \Craft::t('app', 'Max Relations'),
            'settings.minRelations' => \Craft::t('app', 'Min Relations'),
            'settings.viewMode' => \Craft::t('app', 'View Mode'),
            'settings.selectionLabel' => \Craft::t('app', 'Selection Label'),
            'settings.validateRelatedElements' => \Craft::t('app', 'Validate related assets'),
            'settings.previewMode' => \Craft::t('app', 'Preview Mode'),
            'settings.allowSelfRelations' => \Craft::t('app', 'Allow self relations'),
            'settings.allowedKinds' => \Craft::t('activity', 'Allowed file types'),
            'settings.useTargetSite' => \Craft::t('app', 'Relate assets from a specific site?'),
            'settings.showSiteMenu' => \Craft::t('app', 'Show the site menu'),
            'settings.localizeRelations' => \Craft::t('app', 'Manage relations on a per-site basis')
        ];
        $labels[Dropdown::class] = [
            'settings.options' => \Craft::t('app', 'Options')
        ];
        $labels[Checkboxes::class] = $labels[Dropdown::class];
        $labels[MultiSelect::class] = $labels[Dropdown::class];
        $labels[RadioButtons::class] = $labels[Dropdown::class];
        $labels[Color::class] = [
            'settings.defaultColor' => \Craft::t('app', 'Default colour')
        ];
        $labels[Date::class] = [
            'settings.minuteIncrement' => \Craft::t('app', 'Minute Increment'),
            'settings.showTimeZone' => \Craft::t('app', 'Show Time Zone'),
            'settings.showTime' => \Craft::t('app', 'Show time'),
            'settings.showDate' => \Craft::t('app', 'Show date'),
            'settings.min' => \Craft::t('app', 'Min Date'),
            'settings.max' => \Craft::t('app', 'Max Date'),
        ];
        $labels[Time::class] = [
            'settings.minuteIncrement' => \Craft::t('app', 'Minute Increment'),
            'settings.min' => \Craft::t('app', 'Min Time'),
            'settings.max' => \Craft::t('app', 'Max Time'),
        ];
        $labels[Email::class] = [
            'settings.placeholder' => \Craft::t('app', 'Placeholder Text')
        ];
        $labels[Entries::class] = [
            'settings.sources' => \Craft::t('app', 'Sources'),
            'settings.minRelations' => \Craft::t('app', 'Min Relations'),
            'settings.maxRelations' => \Craft::t('app', 'Max Relations'),
            'settings.selectionLabel' => \Craft::t('app', 'Selection Label'),
            'settings.validateRelatedElements' => \Craft::t('app', 'Validate related entries'),
            'settings.allowSelfRelations' => \Craft::t('app', 'Allow self relations'),
            'settings.useTargetSite' => \Craft::t('app', 'Relate assets from a specific site?'),
            'settings.showSiteMenu' => \Craft::t('app', 'Show the site menu'),
            'settings.localizeRelations' => \Craft::t('app', 'Manage relations on a per-site basis')
        ];
        $labels[Users::class] = $labels[Entries::class];
        $labels[Categories::class] = [
            'settings.source' => \Craft::t('app', 'Source'),
            'settings.branchLimit' => \Craft::t('app', 'Branch Limit'),
            'settings.selectionLabel' => \Craft::t('app', 'Selection Label'),
            'settings.validateRelatedElements' => \Craft::t('app', 'Validate related categories'),
            'settings.allowSelfRelations' => \Craft::t('app', 'Allow self relations'),
            'settings.useTargetSite' => \Craft::t('app', 'Relate assets from a specific site?'),
            'settings.showSiteMenu' => \Craft::t('app', 'Show the site menu'),
            'settings.localizeRelations' => \Craft::t('app', 'Manage relations on a per-site basis')
        ];
        $labels[Tags::class] = [
            'settings.source' => \Craft::t('app', 'Source'),
            'settings.selectionLabel' => \Craft::t('app', 'Selection Label'),
            'settings.validateRelatedElements' => \Craft::t('app', 'Validate related tags'),
            'settings.allowSelfRelations' => \Craft::t('app', 'Allow self relations'),
            'settings.useTargetSite' => \Craft::t('app', 'Relate assets from a specific site?'),
            'settings.showSiteMenu' => \Craft::t('app', 'Show the site menu'),
            'settings.localizeRelations' => \Craft::t('app', 'Manage relations on a per-site basis')
        ];
        $labels[Number::class] = [
            'settings.defaultValue' => \Craft::t('app', 'Default Value'),
            'settings.min' => \Craft::t('app', 'Min Value'),
            'settings.max' => \Craft::t('app', 'Max Value'),
            'settings.decimals' => \Craft::t('app', 'Decimal Points'),
            'settings.size' => \Craft::t('app', 'Size'),
            'settings.prefix' => \Craft::t('app', 'Prefix Text'),
            'settings.suffix' => \Craft::t('app', 'Suffix Text'),
            'settings.previewFormat' => \Craft::t('app', 'Preview Format'),
            'settings.previewCurrency' => \Craft::t('activity', 'Preview Currency'),
        ];
        $labels[PlainText::class] = [
            'settings.uiMode' => \Craft::t('app', 'UI Mode'),
            'settings.placeholder' => \Craft::t('app', 'Placeholder Text'),
            'settings.charLimit' => \Craft::t('activity', 'Character Limit'),
            'settings.byteLimit' => \Craft::t('activity', 'Byte Limit'),
            'settings.code' => \Craft::t('app', 'Use a monospaced font'),
            'settings.multiline' => \Craft::t('app', 'Allow line breaks'),
            'settings.initialRows' => \Craft::t('app', 'Initial Rows'),
            'settings.columnType' => \Craft::t('app', 'Column Type')
        ];
        $labels[Lightswitch::class] = [
            'settings.default' => \Craft::t('app', 'Default Value'),
            'settings.offLabel' => \Craft::t('app', 'OFF Label'),
            'settings.onLabel' => \Craft::t('app', 'ON Label'),
        ];
        $labels[Table::class] = [
            'settings.addRowLabel' => \Craft::t('app', 'Add row label'),
            'settings.columnType' => \Craft::t('app', 'Column Type'),
            'settings.columns' => \Craft::t('app', 'Table Columns'),
            'settings.defaults' => \Craft::t('app', 'Default Values'),
            'settings.minRows' => \Craft::t('app', 'Min rows'),
            'settings.maxRows' => \Craft::t('app', 'Max rows'),
        ];
        $labels[Url::class] = [
            'settings.types' => \Craft::t('app', 'Allowed URL Types'),
            'settings.maxLength' => \Craft::t('app', 'Max Length'),
        ];
        $labels[Matrix::class] = [
            'settings.minBlocks' => \Craft::t('app', 'Min Blocks'),
            'settings.maxBlocks' => \Craft::t('app', 'Max Blocks'),
        ];
        $this->labels = $labels;
    }
}