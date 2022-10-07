<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use Ryssbowh\Activity\models\fieldHandlers\elements\Lightswitch;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use Ryssbowh\Activity\models\fieldHandlers\elements\TableDate;
use Ryssbowh\Activity\models\fieldHandlers\elements\TableDropdown;
use Ryssbowh\Activity\models\fieldHandlers\elements\TableTime;
use craft\fields\Table as TableField;

class Table extends ElementFieldHandler
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
        $this->value = $this->buildValues();
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
    public static function getTargets(): array
    {
        return [
            TableField::class
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/table-field';
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
     * @param  array  $newValue
     * @param  array  $oldValue
     * @return array
     */
    protected function buildDirty(array $newValue, array $oldValue): array
    {
        $dirty = [];
        foreach (array_intersect_key($newValue, $oldValue) as $id => $row) {
            $rowDirty = [];
            foreach ($row as $handle => $handler) {
                if (array_key_exists($handle, $oldValue[$id])) {
                    if ($fdirty = $handler->getDirty($oldValue[$id][$handle])) {
                        $rowDirty[$handle] = [
                            'handler' => get_class($handler),
                            'data' => $fdirty
                        ];
                    }
                } else {
                    $rowDirty[$handle] = [
                        'handler' => get_class($handler),
                        'data' => $handler->getDbValue('t')
                    ];
                }
            }
            if ($rowDirty) {
                $dirty[$id] = [
                    'mode' => 'changed',
                    'data' => $rowDirty
                ];
            }
        }
        foreach(array_diff_key($oldValue, $newValue) as $id => $row) {
            $row = [
                'data' => array_map(function ($handler) {
                    return [
                        'handler' => get_class($handler),
                        'data' => $handler->getDbValue('f')
                    ];
                }, $row),
                'mode' => 'removed'
            ];
            $dirty[$id] = $row;
        }
        foreach(array_diff_key($newValue, $oldValue) as $id => $row) {
            $row = [
                'data' => array_map(function ($handler) {
                    return [
                        'handler' => get_class($handler),
                        'data' => $handler->getDbValue('t')
                    ];
                }, $row),
                'mode' => 'added'
            ];
            $dirty[$id] = $row;
        }
        if ($dirty) {
            return [
                'name' => $this->name,
                'rows' => $dirty
            ];
        }
        return [];
    }

    /**
     * Build each rows as field handlers
     * 
     * @return array
     */
    protected function buildValues(): array
    {
        $values = [];
        foreach ($this->value ?? [] as $row) {
            $newRow = [];
            foreach ($this->field->columns as $handle => $col) {
                if (isset($row[$handle])) {
                    $newRow[$handle] = $this->getHandler($handle, $row[$handle]);
                }
            }
            $values[] = $newRow;
        }
        return $values;
    }

    /**
     * Get the handler for a column
     * 
     * @param  string $handle
     * @param  mixed  $value
     * @return FieldHandler
     */
    protected function getHandler(string $handle, $value): FieldHandler
    {
        $col = $this->field->columns[$handle];
        $params = [
            'value' => $value,
            'name' => $col['heading']
        ];
        switch ($col['type']) {
            case 'checkbox':
            case 'lightswitch':
                $class = Plain::class;
                $params['value'] = (bool)$params['value'];
                break;
            case 'date':
                $class = TableDate::class;
                break;
            case 'time':
                $class = TableTime::class;
                break;
            case 'select':
                $class = TableDropdown::class;
                $params['options'] = $col['options'];
                break;
            default:
                $class = Plain::class;
        }
        return (new $class($params));
    }
}