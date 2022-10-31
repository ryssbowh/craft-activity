<?php

namespace Ryssbowh\Activity\models\logs\fields;

use Ryssbowh\Activity\base\logs\ConfigModelLog;
use craft\base\Model;
use craft\fields\Assets;
use craft\fields\Categories;
use craft\fields\Checkboxes;
use craft\fields\Date;
use craft\fields\Dropdown;
use craft\fields\Email;
use craft\fields\Entries;
use craft\fields\Lightswitch;
use craft\fields\Money;
use craft\fields\MultiSelect;
use craft\fields\Number;
use craft\fields\PlainText;
use craft\fields\RadioButtons;
use craft\fields\Table;
use craft\fields\Tags;
use craft\fields\Time;
use craft\fields\Url;
use craft\fields\Users;
use craft\helpers\UrlHelper;

class FieldCreated extends ConfigModelLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created field {name}', ['name' => $this->modelName]);
    }

    /**
     * @inheritDoc
     */
    protected function getModelLink(): string
    {
        return UrlHelper::cpUrl('settings/fields/edit/' . $this->model->id);
    }

    /**
     * @inheritDoc
     */
    protected function loadModel(): ?Model
    {
        return \Craft::$app->fields->getFieldByUid($this->target_uid);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldLabels(): array
    {
        $labels = parent::getFieldLabels();
        $settings = array_map(function ($name) {
            return 'settings.' . $name;
        }, $labels[$this->target_class] ?? []);
        return array_merge($labels['_base'] ?? [], $settings);
    }

    /**
     * @inheritDoc
     */
    protected function _getFieldLabels(): array
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
            'restrictLocation' => \Craft::t('app', 'Restrict assets to a single location'),
            'restrictedLocationSource' => \Craft::t('activity', 'Asset Location Volume'),
            'restrictedLocationSubpath' => \Craft::t('activity', 'Asset Location Subpath'),
            'allowSubfolders' => \Craft::t('app', 'Allow subfolders'),
            'restrictedDefaultUploadSubpath' => \Craft::t('app', 'Default Upload Location'),
            'sources' => \Craft::t('app', 'Sources'),
            'defaultUploadLocationSource' => \Craft::t('activity', 'Default Upload Location Volume'),
            'defaultUploadLocationSubpath' => \Craft::t('activity', 'Default Upload Location Subpath'),
            'showUnpermittedVolumes' => \Craft::t('app', 'Show unpermitted volumes'),
            'showUnpermittedFiles' => \Craft::t('app', 'Show unpermitted files'),
            'restrictFiles' => \Craft::t('app', 'Restrict allowed file types'),
            'allowUploads' => \Craft::t('app', 'Allow uploading directly to the field'),
            'maxRelations' => \Craft::t('app', 'Max Relations'),
            'minRelations' => \Craft::t('app', 'Min Relations'),
            'viewMode' => \Craft::t('app', 'View Mode'),
            'selectionLabel' => \Craft::t('app', 'Selection Label'),
            'validateRelatedElements' => \Craft::t('app', 'Validate related assets'),
            'previewMode' => \Craft::t('app', 'Preview Mode'),
            'allowSelfRelations' => \Craft::t('app', 'Allow self relations'),
            'allowedKinds' => \Craft::t('activity', 'Allowed file types'),
        ];
        $labels[Dropdown::class] = ['options' => \Craft::t('app', 'Options')];
        $labels[Checkboxes::class] = $labels[Dropdown::class];
        $labels[MultiSelect::class] = $labels[Dropdown::class];
        $labels[RadioButtons::class] = $labels[Dropdown::class];
        $labels[Color::class] = ['defaultColor' => \Craft::t('app', 'Default colour')];
        $labels[Date::class] = [
            'dateTime' => \Craft::t('activity', 'Show'),
            'minuteIncrement' => \Craft::t('app', 'Minute Increment'),
            'showTimeZone' => \Craft::t('app', 'Show Time Zone'),
            'min' => \Craft::t('app', 'Min Date'),
            'max' => \Craft::t('app', 'Max Date'),
        ];
        $labels[Time::class] = [
            'minuteIncrement' => \Craft::t('app', 'Minute Increment'),
            'min' => \Craft::t('app', 'Min Time'),
            'max' => \Craft::t('app', 'Max Time'),
        ];
        $labels[Email::class] = ['placeholder' => \Craft::t('app', 'Placeholder Text')];
        $labels[Entries::class] = [
            'sources' => \Craft::t('app', 'Sources'),
            'minRelations' => \Craft::t('app', 'Min Relations'),
            'maxRelations' => \Craft::t('app', 'Max Relations'),
            'selectionLabel' => \Craft::t('app', 'Selection Label'),
            'validateRelatedElements' => \Craft::t('app', 'Validate related entries'),
            'allowSelfRelations' => \Craft::t('app', 'Allow self relations'),
        ];
        $labels[Users::class] = $labels[Entries::class];
        $labels[Categories::class] = [
            'source' => \Craft::t('app', 'Source'),
            'branchLimit' => \Craft::t('app', 'Branch Limit'),
            'selectionLabel' => \Craft::t('app', 'Selection Label'),
            'validateRelatedElements' => \Craft::t('app', 'Validate related categories'),
            'allowSelfRelations' => \Craft::t('app', 'Allow self relations'),
        ];
        $labels[Tags::class] = [
            'source' => \Craft::t('app', 'Source'),
            'selectionLabel' => \Craft::t('app', 'Selection Label'),
            'validateRelatedElements' => \Craft::t('app', 'Validate related tags'),
            'allowSelfRelations' => \Craft::t('app', 'Allow self relations'),
        ];
        $labels[Money::class] = [
            'currency' => \Craft::t('app', 'Currency'),
            'defaultValue' => \Craft::t('app', 'Default Value'),
            'min' => \Craft::t('app', 'Min Value'),
            'max' => \Craft::t('app', 'Max Value'),
            'showCurrency' => \Craft::t('app', 'Show Currency'),
            'size' => \Craft::t('app', 'Size'),
        ];
        $labels[Number::class] = [
            'defaultValue' => \Craft::t('app', 'Default Value'),
            'min' => \Craft::t('app', 'Min Value'),
            'max' => \Craft::t('app', 'Max Value'),
            'decimals' => \Craft::t('app', 'Decimal Points'),
            'size' => \Craft::t('app', 'Size'),
            'prefix' => \Craft::t('app', 'Prefix Text'),
            'suffix' => \Craft::t('app', 'Suffix Text'),
            'previewFormat' => \Craft::t('app', 'Preview Format'),
            'previewCurrency' => \Craft::t('activity', 'Preview Currency'),
        ];
        $labels[PlainText::class] = [
            'uiMode' => \Craft::t('app', 'UI Mode'),
            'placeholder' => \Craft::t('app', 'Placeholder Text'),
            'charLimit' => \Craft::t('activity', 'Character Limit'),
            'byteLimit' => \Craft::t('activity', 'Byte Limit'),
            'code' => \Craft::t('app', 'Use a monospaced font'),
            'multiline' => \Craft::t('app', 'Allow line breaks'),
            'initialRows' => \Craft::t('app', 'Initial Rows'),
            'columnType' => \Craft::t('app', 'Column Type')
        ];
        $labels[Lightswitch::class] = [
            'default' => \Craft::t('app', 'Default Value'),
            'offLabel' => \Craft::t('app', 'OFF Label'),
            'onLabel' => \Craft::t('app', 'ON Label'),
        ];
        $labels[Table::class] = [
            'addRowLabel' => \Craft::t('app', 'Add row label'),
            'columnType' => \Craft::t('app', 'Column Type'),
            'columns' => \Craft::t('app', 'Table Columns'),
            'defaults' => \Craft::t('app', 'Default Values'),
            'minRows' => \Craft::t('app', 'Min rows'),
            'maxRows' => \Craft::t('app', 'Max rows'),
        ];
        $labels[Url::class] = [
            'types' => \Craft::t('app', 'Allowed URL Types'),
            'maxLength' => \Craft::t('app', 'Max Length'),
        ];
        return $labels;
    }
}