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
use yii\base\Event;

/**
 * @since 2.2.0
 */
class RegisterTrackedFieldsEvent extends Event
{
    /**
     * @var array|string
     */
    public $tracked;

    /**
     * @inheritDoc
     */
    public function init()
    {
        $fields = [
            '_base' => ['name', 'type', 'instructions', 'handle', 'fieldGroup', 'instructions', 'searchable', 'translationMethod', 'translationKeyFormat']
        ];
        $fields[Dropdown::class] = ['settings.options'];
        $fields[Checkboxes::class] = ['settings.options'];
        $fields[MultiSelect::class] = ['settings.options'];
        $fields[RadioButtons::class] = ['settings.options'];
        $fields[Assets::class] = ['settings.restrictLocation', 'settings.restrictedLocationSource', 'settings.restrictedLocationSubpath', 'settings.allowSubfolders', 'settings.restrictedDefaultUploadSubpath', 'settings.sources', 'settings.defaultUploadLocationSource', 'settings.defaultUploadLocationSubpath', 'settings.showUnpermittedVolumes', 'settings.showUnpermittedFiles', 'settings.restrictFiles', 'settings.allowUploads', 'settings.maxRelations', 'settings.minRelations', 'settings.viewMode', 'settings.selectionLabel', 'settings.validateRelatedElements', 'settings.previewMode', 'settings.allowSelfRelations', 'settings.allowedKinds', 'settings.useTargetSite', 'settings.showSiteMenu', 'settings.localizeRelations'];
        $fields[Color::class] = ['settings.defaultColor'];
        $fields[Date::class] = ['settings.minuteIncrement', 'settings.showTimeZone', 'settings.showDate', 'settings.showTime', 'settings.min', 'settings.max'];
        $fields[Time::class] = ['settings.minuteIncrement', 'settings.min', 'settings.max'];
        $fields[Email::class] = ['settings.placeholder'];
        $fields[Entries::class] = ['settings.sources', 'settings.minRelations', 'settings.maxRelations', 'settings.selectionLabel', 'settings.validateRelatedElements', 'settings.allowSelfRelations', 'settings.useTargetSite', 'settings.showSiteMenu', 'settings.localizeRelations'];
        $fields[Users::class] = ['settings.sources', 'settings.minRelations', 'settings.maxRelations', 'settings.selectionLabel', 'settings.validateRelatedElements', 'settings.allowSelfRelations', 'settings.localizeRelations'];
        $fields[Categories::class] = ['settings.source', 'settings.branchLimit', 'settings.selectionLabel', 'settings.validateRelatedElements', 'settings.allowSelfRelations', 'settings.useTargetSite', 'settings.showSiteMenu', 'settings.localizeRelations'];
        $fields[Tags::class] = ['settings.source', 'settings.selectionLabel', 'settings.validateRelatedElements', 'settings.allowSelfRelations', 'settings.useTargetSite', 'settings.showSiteMenu', 'settings.localizeRelations'];
        $fields[Money::class] = ['settings.currency', 'settings.defaultValue', 'settings.min', 'settings.max', 'settings.showCurrency', 'settings.size'];
        $fields[Number::class] = ['settings.defaultValue', 'settings.min', 'settings.max', 'settings.decimals', 'settings.prefix', 'settings.suffix', 'settings.size', 'settings.previewFormat', 'settings.previewCurrency'];
        $fields[PlainText::class] = ['settings.uiMode', 'settings.placeholder', 'settings.charLimit', 'settings.byteLimit', 'settings.code', 'settings.multiline', 'settings.initialRows', 'settings.columnType'];
        $fields[Lightswitch::class] = ['settings.default', 'settings.offLabel', 'settings.onLabel'];
        $fields[Table::class] = ['settings.addRowLabel', 'settings.columnType', 'settings.columns', 'settings.defaults', 'settings.minRows', 'settings.maxRows'];
        $fields[Url::class] = ['settings.types', 'settings.maxLength'];
        $fields[Matrix::class] = ['settings.minBlocks', 'settings.maxBlocks', 'settings.propagationMethod'];
        $this->tracked = $fields;
    }
}