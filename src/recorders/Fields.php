<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\events\ConfigEvent;
use craft\fields\Assets;
use craft\fields\Categories;
use craft\fields\Color;
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
use craft\services\Fields as CraftFields;
use craft\services\ProjectConfig;
use yii\base\Event;

class Fields extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_FIELDS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fields')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_FIELDS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fields')->onAdd($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_FIELDS . '.{uid}', function (Event $event) {
            Activity::getRecorder('fields')->onRemove($event);
        });
    }
        
    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'field';
    }

    /**
     * @inheritDoc
     */
    protected function getPathFieldHandler(string $path, array $config): string
    {
        $path = explode('.', $path);
        if ($path[2] == 'settings') {
            $path[2] = 'settings[' . $config['type'] .']';
        }
        $path = implode('.', $path);
        return parent::getPathFieldHandler($path, $config);
    }

    /**
     * @inheritDoc
     */
    protected function modifyParams(array $params, ConfigEvent $event): array
    {
        $params['target_class'] = $event->newValue['type'] ?? $event->oldValue['type'] ?? '';
        return $params;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config)
    {
        $tracked = parent::getTrackedFieldNames($config);
        if (!isset($config['type'])) {
            return $tracked['_base'] ?? [];
        }
        $settings = array_map(function ($name) {
            return 'settings.' . $name;
        }, $tracked[$config['type']] ?? []);
        return array_merge($tracked['_base'] ?? [], $settings);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(array $config): array
    {
        $typings = parent::getTrackedFieldTypings($config);
        if (!isset($config['type'])) {
            return $typings['_base'] ?? [];
        }
        $settings = array_map(function ($name) {
            return 'settings.' . $name;
        }, $typings[$config['type']] ?? []);
        return array_merge($typings['_base'] ?? [], $settings);
    }

    /**
     * @inheritDoc
     */
    protected function _getTrackedFieldNames(): array
    {
        $fields = [
            '_base' => ['name', 'type', 'instructions', 'handle', 'fieldGroup', 'instructions', 'searchable', 'translationMethod', 'translationKeyFormat']
        ];
        $fields[Dropdown::class] = ['options'];
        $fields[Checkboxes::class] = ['options'];
        $fields[MultiSelect::class] = ['options'];
        $fields[RadioButtons::class] = ['options'];
        $fields[Assets::class] = ['restrictLocation', 'restrictedLocationSource', 'restrictedLocationSubpath', 'allowSubfolders', 'restrictedDefaultUploadSubpath', 'sources', 'defaultUploadLocationSource', 'defaultUploadLocationSubpath', 'showUnpermittedVolumes', 'showUnpermittedFiles', 'restrictFiles', 'allowUploads', 'maxRelations', 'minRelations', 'viewMode', 'selectionLabel', 'validateRelatedElements', 'previewMode', 'allowSelfRelations', 'allowedKinds'];
        $fields[Color::class] = ['defaultColor'];
        $fields[Date::class] = ['dateTime', 'minuteIncrement', 'showTimeZone', 'min', 'max'];
        $fields[Time::class] = ['minuteIncrement', 'min', 'max'];
        $fields[Email::class] = ['placeholder'];
        $fields[Entries::class] = ['sources', 'minRelations', 'maxRelations', 'selectionLabel', 'validateRelatedElements', 'allowSelfRelations'];
        $fields[Users::class] = ['sources', 'minRelations', 'maxRelations', 'selectionLabel', 'validateRelatedElements', 'allowSelfRelations'];
        $fields[Categories::class] = ['source', 'branchLimit', 'selectionLabel', 'validateRelatedElements', 'allowSelfRelations'];
        $fields[Tags::class] = ['source', 'selectionLabel', 'validateRelatedElements', 'allowSelfRelations'];
        $fields[Money::class] = ['currency', 'defaultValue', 'min', 'max', 'showCurrency', 'size'];
        $fields[Number::class] = ['defaultValue', 'min', 'max', 'decimals', 'prefix', 'suffix', 'size', 'previewFormat', 'previewCurrency'];
        $fields[PlainText::class] = ['uiMode', 'placeholder', 'charLimit', 'byteLimit', 'code', 'multiline', 'initialRows', 'columnType'];
        $fields[Lightswitch::class] = ['default', 'offLabel', 'onLabel'];
        $fields[Table::class] = ['addRowLabel', 'columnType', 'columns', 'defaults', 'minRows', 'maxRows'];
        $fields[Url::class] = ['types', 'maxLength'];
        return $fields;
    }

    /**
     * @inheritDoc
     */
    protected function _getTrackedFieldTypings(): array
    {
        return [
            '_base' => ['searchable' => 'bool']
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return 'name';
    }
}