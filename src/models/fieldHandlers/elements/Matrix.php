<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\fields\Matrix as MatrixField;

class Matrix extends ElementFieldHandler
{
    /**
     * @var array
     */
    protected $_dirty;

    /**
     * @inheritDoc
     */
    public function init(): void
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
        if (get_class($handler) != get_class($this)) {
            return true;
        }
        return !empty($this->getDirty($handler)['blocks']);
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            MatrixField::class
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/matrix-field';
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
     * @param  array  $newBlocks
     * @param  array  $oldBlocks
     * @return array
     */
    protected function buildDirty(array $newBlocks, array $oldBlocks): array
    {
        $blocks = [];
        foreach (array_intersect_key($newBlocks, $oldBlocks) as $id => $block) {
            $blockIsdirty = false;
            $blockDirty = [
                'mode' => 'changed'
            ];
            foreach ($block['fields'] as $fieldId => $handler) {
                $oldHandler = $oldBlocks[$id]['fields'][$fieldId] ?? null;
                if ($oldHandler and $handler->isDirty($oldHandler)) {
                    $blockIsdirty = true;
                    $blockDirty['fields'][$fieldId] = [
                        'handler' => get_class($handler),
                        'data' => $handler->getDirty($oldHandler)
                    ];
                }
            }
            if ($block['enabled'] != $oldBlocks[$id]['enabled']) {
                $blockIsdirty = true;
                $blockDirty['enabled'] = [
                    't' => $block['enabled'],
                    'f' => $oldBlocks[$id]['enabled']
                ];
            }
            if ($block['name'] != $oldBlocks[$id]['name']) {
                $blockIsdirty = true;
                $blockDirty['name'] = [
                    't' => $block['name'],
                    'f' => $oldBlocks[$id]['name']
                ];
            }
            if ($blockIsdirty) {
                $blocks[$id] = $blockDirty;
            }
        }
        foreach (array_diff_key($newBlocks, $oldBlocks) as $id => $block) {
            $block['fields'] = array_map(function ($handler) {
                return [
                    'handler' => get_class($handler),
                    'data' => $handler->getDbValue('t')
                ];
            }, $block['fields']);
            $block['mode'] = 'added';
            $blocks[$id] = $block;
        }
        foreach (array_diff_key($oldBlocks, $newBlocks) as $id => $block) {
            $block['fields'] = array_map(function ($handler) {
                return [
                    'handler' => get_class($handler),
                    'data' => $handler->getDbValue('f')
                ];
            }, $block['fields']);
            $block['mode'] = 'removed';
            $blocks[$id] = $block;
        }
        return [
            'name' => $this->name,
            'blocks' => $blocks
        ];
    }

    /**
     * Build the value
     *
     * @return array
     */
    protected function buildValues(): array
    {
        $value = [];
        $blocks = $this->field->normalizeValue($this->rawValue, $this->element)->all();
        foreach ($blocks as $block) {
            $fields = [];
            foreach ($this->field->getBlockTypeFields([$block->type->id]) as $field) {
                $fields[$field->id] = Activity::$plugin->fieldHandlers->getHandlerForField($field, $block);
            }
            $value[] = [
                'enabled' => $block->enabled,
                'handle' => $block->type->handle,
                'name' => $block->type->name,
                'fields' => $fields
            ];
        }
        return $value;
    }
}
