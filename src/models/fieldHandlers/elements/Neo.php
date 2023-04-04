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
            $blockIsdirty = false;
            $blockDirty = [
                'mode' => 'changed'
            ];
            foreach ($block['fields'] as $fieldId => $handler) {
                if ($fdirty = $handler->getDirty($oldBlocks[$id]['fields'][$fieldId])) {
                    $blockIsdirty = true;
                    $blockDirty['fields'][$fieldId] = [
                        'handler' => get_class($handler),
                        'data' => $fdirty
                    ];
                }
            }
            if ($children = $this->buildDirtyBlocks($block['children'], $oldBlocks[$id]['children'])) {
                $blockDirty['children'] = $children;
                $blockIsdirty = true;
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
            $block['children'] = $this->buildDirtyBlocks($block['children'], []);
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
            $block['children'] = $this->buildDirtyBlocks([], $block['children']);
            $block['mode'] = 'removed';
            $blocks[$id] = $block;
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
        foreach ($this->rawValue->anyStatus()->level(1)->all() as $id => $block) {
            $value[] = $this->buildBlockValues($block);
        }
        return $value;
    }

    /**
     * Build the value for one block
     *
     * @param  Block  $block
     * @return array
     */
    protected function buildBlockValues(Block $block): array
    {
        $fields = [];
        foreach ($block->getFieldLayout()->getTabs() as $tab) {
            foreach ($tab->elements as $elem) {
                if (!$elem instanceof CustomField) {
                    continue;
                }
                $field = $elem->field;
                $class = Activity::$plugin->fieldHandlers->getForElementField(get_class($field));
                $fields[$field->id] = new $class([
                    'field' => $field,
                    'element' => $block,
                    'name' => $field->name,
                    'value' => $field->serializeValue($block->{$field->handle}, $block),
                    'rawValue' => $block->{$field->handle}
                ]);
            }
        }
        $value = [
            'fields' => $fields,
            'children' => []
        ];
        if ($children = $block->getChildren()) {
            foreach ($children as $child) {
                $value['children'][] = $this->buildBlockValues($child);
            }
        }
        return $value;
    }
}
