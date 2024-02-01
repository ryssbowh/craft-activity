<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\fieldlayoutelements\CustomField;
use verbb\vizy\base\Node;
use verbb\vizy\nodes\VizyBlock;

/**
 * @since 2.4.0
 */
class Vizy extends ElementFieldHandler
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
            'verbb\\vizy\\fields\\VizyField'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/vizy-field';
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
     * Build the value
     *
     * @return array
     */
    protected function buildValues(): array
    {
        $value = [];
        $nodes = $this->field->normalizeValue($this->rawValue, $this->element)->getNodes();
        $currentText = '';
        $mode = null;
        foreach ($nodes as $node) {
            if ($node instanceof VizyBlock) {
                if ($mode == 'text') {
                    $value[] = [
                        'type' => 'text',
                        'value' => $currentText
                    ];
                }
                $value[] = $this->buildBlockValue($node);
                $mode = null;
                $currentText = '';
            } else {
                $mode = 'text';
                $currentText .= $node->renderNode();
            }
        }
        if ($mode == 'text') {
            $value[] = [
                'type' => 'text',
                'value' => $currentText
            ];
        }
        return $value;
    }

    /**
     * Build the value for one block
     *
     * @param  VizyBlock  $block
     * @return array
     */
    protected function buildBlockValue(VizyBlock $block): array
    {
        $fields = [];
        foreach ($block->getFieldLayout()->getTabs() as $tab) {
            foreach ($tab->elements as $elem) {
                if (!$elem instanceof CustomField) {
                    continue;
                }
                $field = $elem->field;
                //Vizy fields don't actually create neo/super table blocks, they save them all as json which makes it impossible to calculate changes on
                if (get_class($field) == 'benf\\neo\\Field') {
                    continue;
                }
                $fields[$field->id] = Activity::$plugin->fieldHandlers->getHandlerForField($field, $block->getBlockElement($this->element));
            }
        }
        return [
            'type' => 'block',
            'handle' => $block->getBlockType()->handle,
            'id' => $block->attrs['id'],
            'enabled' => $block->attrs['enabled'],
            'fields' => $fields
        ];
    }

    /**
     * Build dirty nodes
     *
     * @param  array  $newBlocks
     * @param  array  $oldBlocks
     * @return array
     */
    protected function buildDirtyBlocks(array $newBlocks, array $oldBlocks): array
    {
        $newMatches = $oldMatches = $blocks = [];
        foreach ($newBlocks as $index => $block) {
            list($oldBlock, $oldIndex) = $this->matchBlock($block, $oldBlocks, $oldMatches);
            if (!$oldBlock) {
                continue;
            }
            $newMatches[] = $index;
            $oldMatches[] = $oldIndex;
            $blockIsDirty = false;
            $blockDirty = [
                'mode' => 'changed',
                'index' => $index,
                'type' => $block['type']
            ];
            if ($block['type'] == 'block') {
                if ($block['enabled'] != $oldBlock['enabled']) {
                    $blockIsDirty = true;
                    $blockDirty['enabled'] = [
                        't' => $block['enabled'],
                        'f' => $oldBlock['enabled']
                    ];
                }
                if ($block['handle'] != $oldBlock['handle']) {
                    $blockIsDirty = true;
                    $blockDirty['handle'] = [
                        't' => $block['handle'],
                        'f' => $oldBlock['handle']
                    ];
                }
                foreach ($block['fields'] as $fieldId => $handler) {
                    $oldHandler = $oldBlock['fields'][$fieldId] ?? null;
                    if ($oldHandler and $handler->isDirty($oldHandler)) {
                        $blockIsDirty = true;
                        $blockDirty['fields'][$fieldId] = [
                            'handler' => get_class($handler),
                            'data' => $handler->getDirty($oldHandler)
                        ];
                    }
                }
            } elseif ($block['value'] != $oldBlock['value']) {
                $blockIsDirty = true;
                $blockDirty['value'] = [
                    't' => $block['value'],
                    'f' => $oldBlock['value']
                ];
            }
            if ($blockIsDirty) {
                $blocks[] = $blockDirty;
            }
        }
        foreach ($newBlocks as $index => $block) {
            if (in_array($index, $newMatches)) {
                continue;
            }
            $dirty = [
                'mode' => 'added',
                'index' => $index,
                'type' => $block['type']
            ];
            if ($block['type'] == 'block') {
                $dirty['fields'] = array_map(function ($handler) {
                    return [
                        'handler' => get_class($handler),
                        'data' => $handler->getDbValue('t')
                    ];
                }, $block['fields']);
                $dirty['enabled'] = $block['enabled'];
                $dirty['handle'] = $block['handle'];
            } else {
                $dirty['value'] = ['t' => $block['value']];
            }
            $blocks[] = $dirty;
        }
        foreach ($oldBlocks as $index => $block) {
            if (in_array($index, $oldMatches)) {
                continue;
            }
            $dirty = [
                'mode' => 'removed',
                'index' => $index,
                'type' => $block['type']
            ];
            if ($block['type'] == 'block') {
                $dirty['fields'] = array_map(function ($handler) {
                    return [
                        'handler' => get_class($handler),
                        'data' => $handler->getDbValue('f')
                    ];
                }, $block['fields']);
                $dirty['enabled'] = $block['enabled'];
                $dirty['handle'] = $block['handle'];
            } else {
                $dirty['value'] = ['f' => $block['value']];
            }
            $blocks[] = $dirty;
        }
        return $blocks;
    }

    /**
     * Match a block with an array of old blocks.
     * If the block is if type 'block' we'll match by id, otherwise we simply match.
     * returns the block matched and the index in the old blocks
     *
     * @param  array  $block
     * @param  array  $oldBlocks
     * @param  array  $ignore
     * @return array
     */
    protected function matchBlock(array $block, array $oldBlocks, array $ignore): array
    {
        foreach ($oldBlocks as $index => $oldBlock) {
            if (in_array($index, $ignore)) {
                continue;
            }
            if ($block['type'] == 'text' and $oldBlock['type'] == 'text') {
                return [$oldBlock, $index];
            }
            if ($block['type'] == 'block' and $oldBlock['type'] == 'block' and $block['id'] == $oldBlock['id']) {
                return [$oldBlock, $index];
            }
        }
        return [null, null];
    }
}
