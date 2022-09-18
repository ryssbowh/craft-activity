<?php

namespace Ryssbowh\Activity\models\fieldHandlers\config;

use Ryssbowh\Activity\base\FieldHandler;
use craft\helpers\Json;
use craft\models\CategoryGroup;
use craft\models\Section;

class PreviewTargets extends DefaultHandler
{
    protected $_dirty;
    
    public function init()
    {
        parent::init();
        if (is_string($this->rawValue)) {
            $this->rawValue = Json::decode($this->rawValue);
        }
        $this->value = $this->buildValues($this->rawValue);
    }

    public static function getTargets(): array
    {
        return [
            Section::class => 'previewTargets',
            CategoryGroup::class => 'previewTargets'
        ];
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

    protected function buildValues(array $targets): array
    {
        $values = [];
        foreach ($targets as $index => $target) {
            $value = [
                'label' => $target['label'],
                'urlFormat' => $target['urlFormat']
            ];
            if (array_key_exists('refresh', $target)) {
                $value['target'] = (bool)$target['refresh'];
            }
            $values[] = $value;
        }
        return $values;
    }

    protected function buildDirty(array $newFields, array $oldFields): array
    {
        $dirty = [];
        foreach ($newFields as $id => $values) {
            if (array_key_exists($id, $oldFields)) {
                $rDirty = [];
                foreach (['label', 'urlFormat', 'refresh'] as $field) {
                    if (array_key_exists($field, $values) and $values[$field] !== $oldFields[$id][$field]) {
                        $rDirty['mode'] = 'changed';
                        $rDirty['row'] = $this->getRowLabel($id + 1);
                        $rDirty[$field]['f'] = $oldFields[$id][$field];
                        $rDirty[$field]['t'] = $values[$field];
                    }    
                }
                if ($rDirty) {
                    $dirty[] = $rDirty;
                }
            } else {
                $value = [
                    'mode' => 'added',
                    'row' => $this->getRowLabel($id + 1),
                    'urlFormat' => ['t' => $values['urlFormat']],
                    'label' => ['t' => $values['label']],
                ];
                if (array_key_exists('refresh', $values)) {
                    $value['refresh'] = ['t' => $values['refresh']];
                }
                $dirty[] = $value;
            }
        }
        foreach (array_diff_key($oldFields, $newFields) as $id => $values) {
            $value = [
                'mode' => 'removed',
                'row' => $this->getRowLabel($id + 1),
                'urlFormat' => ['f' => $values['urlFormat']],
                'label' => ['f' => $values['label']]
            ];
            if (array_key_exists('refresh', $values)) {
                $value['refresh'] = ['f' => $values['refresh']];
            }
            $dirty[] = $value;
        }
        return $dirty;
    }

    protected function getRowLabel(int $id): string
    {
        $id = (string)$id;
        if (substr($id, -1) == '1') {
            return $id . 'st';
        } elseif (substr($id, -1) == '2') {
            return $id . 'nd';
        } elseif (substr($id, -1) == '3') {
            return $id . 'rd';
        }
        return $id . 'th';
    }
}