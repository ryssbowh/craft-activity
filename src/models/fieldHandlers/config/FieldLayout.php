<?php

namespace Ryssbowh\Activity\models\fieldHandlers\config;

use Ryssbowh\Activity\base\FieldHandler;
use craft\elements\GlobalSet;
use craft\fieldlayoutelements\CustomField;
use craft\models\CategoryGroup;
use craft\models\EntryType;
use craft\models\FieldLayout as FieldLayoutModel;
use craft\models\TagGroup;

class FieldLayout extends DefaultHandler
{
    protected $_dirty;
    
    public function init()
    {
        parent::init();
        $this->value = $this->buildValues($this->rawValue);
    }

    public static function getTargets(): array
    {
        $targets = [
            EntryType::class => 'fieldLayout',
            CategoryGroup::class => 'fieldLayout',
            GlobalSet::class => 'fieldLayout',
            TagGroup::class => 'fieldLayout',
        ];
        foreach (\Craft::$app->volumes->getAllVolumeTypes() as $class) {
            $targets[$class] = 'fieldLayout';
        }
        return $targets;
    }

    public function getDirty(FieldHandler $handler): array
    {
        if ($this->_dirty === null) {
            $this->_dirty = $this->buildDirty($this->value, $handler->value);
        }
        return $this->_dirty;
    }

    public function isDirty(FieldHandler $handler): bool
    {
        return !empty($this->getDirty($handler));
    }

    protected function buildValues(FieldLayoutModel $fieldLayout): array
    {
        $values = [];
        foreach ($fieldLayout->tabs as $tab) {
            foreach ($tab->elements as $element) {
                if ($element instanceof CustomField) {
                    $values[$element->field->id] = [
                        'name' => $element->field->name
                    ];
                }
            }
        }
        return $values;
    }

    protected function buildDirty(array $newFields, array $oldFields): array
    {
        $dirty = [];
        $added = array_diff_key($newFields, $oldFields);
        $removed = array_diff_key($oldFields, $newFields);
        if ($added) {
            $dirty['added'] = $added;
        }
        if ($removed) {
            $dirty['removed'] = $removed;
        }
        return $dirty;
    }

    public function getDbValue(string $valueKey): array
    {
        return $this->buildDirty($this->value, []);
    }
}