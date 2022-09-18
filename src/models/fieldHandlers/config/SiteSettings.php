<?php

namespace Ryssbowh\Activity\models\fieldHandlers\config;

use Ryssbowh\Activity\base\FieldHandler;
use craft\models\CategoryGroup;
use craft\models\Section;

class SiteSettings extends DefaultHandler
{
    protected $_dirty;

    public function init()
    {
        parent::init();
        $this->value = $this->buildValues($this->rawValue);
    }

    public static function getTargets(): array
    {
        return [
            Section::class => 'siteSettings',
            CategoryGroup::class => 'siteSettings'
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

    protected function buildValues(array $siteSettings): array
    {
        $values = [];
        foreach ($siteSettings as $settings) {
            $values[$settings->site->id] = [
                'template' => $settings->template ?? '',
                'uriFormat' => $settings->uriFormat ?? '',
                'name' => $settings->site->name,
                'id' => $settings->site->id
            ];
        }
        return $values;
    }

    protected function buildDirty(array $newFields, array $oldFields): array
    {
        $dirty = [];
        foreach ($newFields as $id => $values) {
            if (array_key_exists($id, $oldFields)) {
                $rowDirty = false;
                $rDirty = [
                    'name' => $values['name'],
                    'id' => $values['id'],
                    'mode' => 'changed'
                ];
                foreach (['template', 'uriFormat'] as $field) {
                    if (($values[$field] ?? null) !== ($oldFields[$id][$field] ?? null)) {
                        $rowDirty = true;
                        $rDirty[$field]['f'] = $oldFields[$id][$field];
                        $rDirty[$field]['t'] = $values[$field];
                    }
                }
                if ($rowDirty) {
                    $dirty[] = $rDirty;
                }
            } else {
                $dirty[] = [
                    'name' => $values['name'],
                    'id' => $values['id'],
                    'mode' => 'enabled',
                    'uriFormat' => ['t' => $values['uriFormat']],
                    'template' => ['t' => $values['template']]
                ];
            }
        }
        foreach (array_diff_key($oldFields, $newFields) as $id => $values) {
            $dirty[] = [
                'name' => $values['name'],
                'id' => $values['id'],
                'mode' => 'disabled',
                'uriFormat' => ['f' => $values['uriFormat']],
                'template' => ['f' => $values['template']]
            ];
        }
        return $dirty;
    }
}