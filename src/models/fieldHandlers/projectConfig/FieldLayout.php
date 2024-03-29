<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\fieldlayoutelements\CustomField;
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
            ProjectConfig::PATH_MATRIX_BLOCK_TYPES . '.{uid}.fieldLayouts'
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
                    'label' => \Craft::t('app', 'Required'),
                    'f' => (bool)$oldFields[$uid]['required'],
                    't' => (bool)$newFields[$uid]['required']
                ];
            }
            if (($newFields[$uid]['label'] ?? null) !== ($oldFields[$uid]['label'] ?? null)) {
                $rowIsdirty = true;
                $rowDirty['label'] = [
                    'label' => \Craft::t('app', 'Label'),
                    'f' => $oldFields[$uid]['label'],
                    't' => $newFields[$uid]['label']
                ];
            }
            if (($newFields[$uid]['instructions'] ?? null) !== ($oldFields[$uid]['instructions'] ?? null)) {
                $rowIsdirty = true;
                $rowDirty['instructions'] = [
                    'label' => \Craft::t('app', 'Instructions'),
                    'f' => $oldFields[$uid]['instructions'],
                    't' => $newFields[$uid]['instructions']
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
                if ($element['type'] == CustomField::class) {
                    $field = \Craft::$app->fields->getFieldByUid($element['fieldUid']);
                    $values[$element['fieldUid']] = [
                        'name' => $field ? $field->name : '*deleted field*',
                        'required' => $element['required'] ?? ''
                    ];
                    if (isset($element['label'])) {
                        $values[$element['fieldUid']]['label'] = $element['label'];
                    }
                    if (isset($element['instructions'])) {
                        $values[$element['fieldUid']]['instructions'] = $element['instructions'];
                    }
                    if (isset($element['width'])) {
                        $values[$element['fieldUid']]['width'] = $element['width'];
                    }
                }
            }
        }
        return $values;
    }
}
