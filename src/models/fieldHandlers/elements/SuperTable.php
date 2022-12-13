<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;

class SuperTable extends ElementFieldHandler
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
            'verbb\\supertable\\fields\\SuperTableField'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/supertable-field';
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
                if ($fdirty = $handler->getDirty($oldBlocks[$id]['fields'][$fieldId])) {
                    $blockIsdirty = true;
                    $blockDirty['fields'][$fieldId] = [
                        'handler' => get_class($handler),
                        'data' => $fdirty
                    ];
                }
            }
            if ($blockIsdirty) {
                $blocks[$id] = $blockDirty;
            }
        }
        foreach(array_diff_key($newBlocks, $oldBlocks) as $id => $block) {
            $block['fields'] = array_map(function ($handler) {
                return [
                    'handler' => get_class($handler),
                    'data' => $handler->getDbValue('t')
                ];
            }, $block['fields']);
            $block['mode'] = 'added';
            $blocks[$id] = $block;
        }
        foreach(array_diff_key($oldBlocks, $newBlocks) as $id => $block) {
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
        foreach ($this->rawValue->anyStatus()->all() as $id => $block) {
            $fields = [];
            foreach ($this->field->getBlockTypeFields([$block->type->id]) as $field) {
                $class = Activity::$plugin->fieldHandlers->getForElementField(get_class($field));
                $fields[$field->id] = new $class([
                    'field' => $field,
                    'element' => $block,
                    'name' => $field->name,
                    'value' => $field->serializeValue($block->{$field->handle}, $block),
                    'rawValue' => $block->{$field->handle}
                ]);
            }
            $value[] = [
                'fields' => $fields
            ];
        }
        return $value;
    }
}