<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\fieldlayoutelements\CustomField;
use craft\helpers\ProjectConfig;
use craft\services\Categories;
use craft\services\Globals;
use craft\services\Sections;
use craft\services\Tags;
use craft\services\Users;
use craft\services\Volumes;

class FieldLayout extends DefaultHandler
{
    /**
     * @var array
     */
    protected $_dirty;
    
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->value = $this->buildValues($this->value);
    }

    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            Sections::CONFIG_ENTRYTYPES_KEY . '.{uid}.fieldLayouts',
            Categories::CONFIG_CATEGORYROUP_KEY . '.{uid}.fieldLayouts',
            Globals::CONFIG_GLOBALSETS_KEY . '.{uid}.fieldLayouts',
            Tags::CONFIG_TAGGROUP_KEY . '.{uid}.fieldLayouts',
            Volumes::CONFIG_VOLUME_KEY . '.{uid}.fieldLayouts',
            Users::CONFIG_USERS_KEY . '.fieldLayouts',
        ];
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
     * Build values for a field layout config
     * 
     * @param  array $fieldLayout
     * @return array
     */
    protected function buildValues(array $fieldLayout): array
    {
        $fieldLayout = ProjectConfig::unpackAssociativeArrays($fieldLayout);
        $values = [];
        $fieldLayout = reset($fieldLayout);
        foreach ($fieldLayout['tabs'] as $tab) {
            foreach ($tab['elements'] as $element) {
                if ($element['type'] == CustomField::class) {
                    $field = \Craft::$app->fields->getFieldByUid($element['fieldUid']);
                    $values[$element['fieldUid']] = [
                        'name' => $field->name,
                        'label' => $element['label'],
                        'required' => $element['required']
                    ];
                }
            }
        }
        return $values;
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
            if ($newFields[$uid]['label'] !== $oldFields[$uid]['label']) {
                $rowIsdirty = true;
                $rowDirty['label'] = [
                    'label' => \Craft::t('app', 'Label'),
                    'f' => $oldFields[$uid]['label'],
                    't' => $newFields[$uid]['label']
                ];
            }
            if ($rowIsdirty) {
                $dirty['changed'][$uid] = $rowDirty;
            }
        }
        $added = array_diff_key($newFields, $oldFields);
        if ($added) {
            $dirty['added'] = $added;
        }
        $removed = array_diff_key($oldFields, $newFields);
        if ($removed) {
            $dirty['removed'] = $removed;
        }
        return $dirty;
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
}