<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use benf\neo\elements\Block;
use craft\fieldlayoutelements\CustomField;

/**
 * @since 2.3.1
 */
class Neo extends ElementFieldHandler
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
            'benf\\neo\\Field'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/neo-field';
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
        return [
            'name' => $this->name,
            'blocks' => $this->buildDirtyBlocks($newBlocks, $oldBlocks)
        ];
    }

    /**
     * Build dirty values
     *
     * @param  array  $newBlocks
     * @param  array  $oldBlocks
     * @return array
     */
    protected function buildDirtyBlocks(array $newBlocks, array $oldBlocks): array
    {
        $blocks = [];
        foreach (array_intersect_key($newBlocks, $oldBlocks) as $id => $block) {
            $blockIsDirty = false;
            $blockDirty = [
                'order' => $block['order'],
                'mode' => 'changed'
            ];
            $oldBlock = $oldBlocks[$id];
            if ($block['order'] != $oldBlock['order']) {
                $blockIsDirty = true;
                $blockDirty['order'] = [
                    'f' => $oldBlock['order'],
                    't' => $block['order'],
                ];
            }
            if ($block['enabled'] != $oldBlock['enabled']) {
                $blockIsDirty = true;
                $blockDirty['enabled'] = [
                    'f' => $oldBlock['enabled'],
                    't' => $block['enabled'],
                ];
            }
            if ($block['type'] != $oldBlock['type']) {
                $blockIsDirty = true;
                $blockDirty['type'] = [
                    'f' => $oldBlock['type'],
                    't' => $block['type'],
                ];
            }
            foreach ($block['fields'] as $fieldId => $handler) {
                if ($oldHandler = ($oldBlock['fields'][$fieldId] ?? null)) {
                    if ($handler->isDirty($oldHandler)) {
                        $blockIsDirty = true;
                        $blockDirty['fields'][] = [
                            'handler' => get_class($handler),
                            'data' => $handler->getDirty($oldHandler)
                        ];
                    }
                } else {
                    $blockIsDirty = true;
                    $blockDirty['fields'][] = [
                        'handler' => get_class($handler),
                        'data' => $handler->getDbValue('t')
                    ];
                }
            }
            foreach ($oldBlock['fields'] as $fieldId => $oldHandler) {
                if (!isset($block['fields'][$fieldId])) {
                    $blockIsDirty = true;
                    $blockDirty['fields'][] = [
                        'handler' => get_class($oldHandler),
                        'data' => $oldHandler->getDbValue('f')
                    ];
                }
            }
            if ($children = $this->buildDirtyBlocks($block['children'], $oldBlock['children'])) {
                $blockDirty['children'] = $children;
                $blockIsDirty = true;
            }
            if ($blockIsDirty) {
                $blocks[] = $blockDirty;
            }
        }
        foreach ($newBlocks as $id => $block) {
            if (!isset($oldBlocks[$id])) {
                $dirty = [
                    'order' => $block['order'],
                    'mode' => 'added',
                    'enabled' => $block['enabled'],
                    'type' => $block['type'],
                    'fields' => array_map(function ($handler) {
                        return [
                            'handler' => get_class($handler),
                            'data' => $handler->getDbValue('t')
                        ];
                    }, $block['fields']),
                    'children' => $this->buildDirtyBlocks($block['children'], [])
                ];
                $blocks[] = $dirty;
            }
        }
        foreach ($oldBlocks as $id => $block) {
            if (!isset($newBlocks[$id])) {
                $dirty = [
                    'order' => $block['order'],
                    'mode' => 'removed',
                    'enabled' => $block['enabled'],
                    'type' => $block['type'],
                    'fields' => array_map(function ($handler) {
                        return [
                            'handler' => get_class($handler),
                            'data' => $handler->getDbValue('f')
                        ];
                    }, $block['fields']),
                    'children' => $this->buildDirtyBlocks([], $block['children'])
                ];
                $blocks[] = $dirty;
            }
        }
        return $blocks;
    }

    /**
     * Build the value
     *
     * @return array
     */
    protected function buildValues(): array
    {
        $value = [];
        $blocks = $this->field->normalizeValue($this->rawValue)->anyStatus()->all();
        $children = [];
        foreach ($blocks as $block) {
            $children[$block->id] = $block->getChildren()->anyStatus()->all();
        }
        $order = 1;
        foreach ($blocks as $block) {
            if ($block->level == 1) {
                $value[$block->id] = $this->buildBlockValues($block, $children, $order);
                $order++;
            }
        }
        return $value;
    }

    /**
     * Build the value for one block
     *
     * @param  Block  $block
     * @param  array $children Children for all blocks
     * @param  int $order
     * @return array
     */
    protected function buildBlockValues(Block $block, array $children, int $order): array
    {
        $fields = [];
        foreach ($block->getFieldLayout()->getTabs() as $tab) {
            foreach ($tab->elements as $elem) {
                if (!$elem instanceof CustomField) {
                    continue;
                }
                $field = $elem->field;
                $fields[$field->id] = Activity::$plugin->fieldHandlers->getHandlerForField($field, $block);
            }
        }
        $value = [
            'order' => $order,
            'fields' => $fields,
            'enabled' => $block->enabled,
            'type' => $block->type->handle,
            'children' => []
        ];
        $order = 1;
        foreach ($children[$block->id] ?? [] as $child) {
            $value['children'][$child->id] = $this->buildBlockValues($child, $children, $order);
            $order++;
        }
        return $value;
    }
}
