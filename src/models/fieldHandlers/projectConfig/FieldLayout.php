<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\fieldlayoutelements\BaseField;
use craft\fieldlayoutelements\CustomField;
use craft\fieldlayoutelements\addresses\AddressField;
use craft\helpers\ProjectConfig as ProjectConfigHelper;
use craft\services\ProjectConfig;

class FieldLayout extends DefaultHandler
{
    /**
     * @var array
     */
    protected $_dirty;

    public bool $unpacked = false;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        $this->value = $this->buildValues($this->value);
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_ENTRY_TYPES . '.{uid}.fieldLayouts',
            ProjectConfig::PATH_CATEGORY_GROUPS . '.{uid}.fieldLayouts',
            ProjectConfig::PATH_GLOBAL_SETS . '.{uid}.fieldLayouts',
            ProjectConfig::PATH_TAG_GROUPS . '.{uid}.fieldLayouts',
            ProjectConfig::PATH_VOLUMES . '.{uid}.fieldLayouts',
            ProjectConfig::PATH_USER_FIELD_LAYOUTS,
            ProjectConfig::PATH_ADDRESS_FIELD_LAYOUTS,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/field-layout';
    }

    /**
     * @inheritDoc
     */
    public function getDirty(FieldHandler $handler): array
    {
        if ($this->_dirty === null) {
            $this->_dirty = $this->buildDirty($this->value, $handler->value);
        }
        return $this->_dirty;
    }

    /**
     * @inheritDoc
     */
    public function isDirty(FieldHandler $handler): bool
    {
        return !empty($this->getDirty($handler));
    }

    /**
     * @inheritDoc
     */
    public function getDbValue(string $valueKey): array
    {
        if ($valueKey == 'f') {
            return $this->buildDirty([], $this->value);
        }
        return $this->buildDirty($this->value, []);
    }

    /**
     * Build dirty values
     *
     * @param  array  $newFields
     * @param  array  $oldFields
     * @return array
     */
    protected function buildDirty(array $newFields, array $oldFields): array
    {
        $dirty = [];
        foreach (array_intersect_key($newFields, $oldFields) as $uid => $field) {
            $rowIsdirty = false;
            $rowDirty = [
                'name' => $newFields[$uid]['name']
            ];
            if ((bool)$newFields[$uid]['required'] !== (bool)$oldFields[$uid]['required']) {
                $rowIsdirty = true;
                $rowDirty['required'] = [
                    'f' => (bool)$oldFields[$uid]['required'],
                    't' => (bool)$newFields[$uid]['required']
                ];
            }
            if (($newFields[$uid]['label'] ?? null) !== ($oldFields[$uid]['label'] ?? null)) {
                $rowIsdirty = true;
                $rowDirty['label'] = [
                    'f' => $oldFields[$uid]['label'] ?? '',
                    't' => $newFields[$uid]['label'] ?? ''
                ];
            }
            if (($newFields[$uid]['instructions'] ?? null) !== ($oldFields[$uid]['instructions'] ?? null)) {
                $rowIsdirty = true;
                $rowDirty['instructions'] = [
                    'f' => $oldFields[$uid]['instructions'] ?? '',
                    't' => $newFields[$uid]['instructions'] ?? ''
                ];
            }
            if (($newFields[$uid]['includeInCards'] ?? null) !== ($oldFields[$uid]['includeInCards'] ?? null)) {
                $rowIsdirty = true;
                $rowDirty['includeInCards'] = [
                    'f' => $oldFields[$uid]['includeInCards'] ?? false,
                    't' => $newFields[$uid]['includeInCards'] ?? false
                ];
            }
            if ($rowIsdirty) {
                $dirty['changed'][$uid] = $rowDirty;
            }
        }
        $added = array_diff_key($newFields, $oldFields);
        if ($added) {
            $dirty['added'] = $added;
            foreach ($dirty['added'] as $index => $rm) {
                $dirty['added'][$index]['required'] = (bool)$dirty['added'][$index]['required'];
            }
        }
        $removed = array_diff_key($oldFields, $newFields);
        if ($removed) {
            $dirty['removed'] = $removed;
            foreach ($dirty['removed'] as $index => $rm) {
                $dirty['removed'][$index]['required'] = (bool)$dirty['removed'][$index]['required'];
            }
        }
        return $dirty;
    }

    /**
     * Build values for a field layout config
     *
     * @param  array $fieldLayout
     * @return array
     */
    protected function buildValues(array $fieldLayout): array
    {
        if (!$this->unpacked) {
            $fieldLayout = ProjectConfigHelper::unpackAssociativeArrays($fieldLayout);
        }
        $values = [];
        $fieldLayout = reset($fieldLayout);
        foreach ($fieldLayout['tabs'] ?? [] as $tab) {
            foreach ($tab['elements'] ?? [] as $element) {
                $uid = false;
                if ($element['type'] == CustomField::class) {
                    $field = \Craft::$app->fields->getFieldByUid($element['fieldUid']);
                    $uid = $element['uid'];
                    $name = $field ? $field->name : '*deleted field*';
                } elseif ($element['type'] == AddressField::class) {
                    $uid = $element['uid'];
                    $name = \Craft::t('app', 'Address');
                } elseif (is_subclass_of($element['type'], BaseField::class)) {
                    $uid = $element['uid'];
                    $name = $element['label'] ?? (new $element['type']())->label();
                }
                if ($uid) {
                    $values[$uid] = [
                        'name' => $name,
                        'required' => $element['required'] ?? ''
                    ];
                    if (isset($element['label']) and $element['label'] != $name) {
                        $values[$uid]['label'] = $element['label'];
                    }
                    if (isset($element['instructions'])) {
                        $values[$uid]['instructions'] = $element['instructions'];
                    }
                    if (isset($element['width'])) {
                        $values[$uid]['width'] = $element['width'];
                    }
                    if (isset($element['includeInCards'])) {
                        $values[$uid]['includeInCards'] = $element['includeInCards'];
                    }
                }
            }
        }
        return $values;
    }
}
