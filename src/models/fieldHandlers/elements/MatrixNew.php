<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\fields\Matrix as MatrixField;

/**
 * @since 3.0.0
 */
class MatrixNew extends Matrix
{
    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/matrix-field-new';
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
            if ($block['enabled'] != $oldBlocks[$id]['enabled']) {
                $blocks[$id] = [
                    'mode' => 'changed',
                    'title' => $block['title'],
                    't' => $block['enabled'],
                    'f' => $oldBlocks[$id]['enabled']
                ];
            }
        }
        foreach (array_diff_key($newBlocks, $oldBlocks) as $id => $block) {
            $blocks[$id] = [
                'mode' => 'added',
                'title' => $block['title']
            ];
        }
        foreach (array_diff_key($oldBlocks, $newBlocks) as $id => $block) {
            $blocks[$id] = [
                'mode' => 'removed',
                'title' => $block['title']
            ];
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
        $entries = (clone $this->rawValue)->anyStatus()->all();
        foreach ($entries as $entry) {
            $canonical = $entry->getCanonical();
            $value[$canonical->id] = [
                'enabled' => $entry->enabled,
                'title' => $entry->title
            ];
        }
        return $value;
    }
}
