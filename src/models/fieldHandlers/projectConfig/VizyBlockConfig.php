<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\FieldLayout;
use craft\base\Field;
use craft\fieldlayoutelements\CustomField;
use craft\helpers\ProjectConfig as ProjectConfigHelper;
use craft\services\ProjectConfig;

/**
 * @since 2.4.0
 */
class VizyBlockConfig extends DefaultHandler
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
        $this->value = $this->buildValues($this->value);
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/vizy-block-types';
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
     * @param  array  $newFields
     * @param  array  $oldFields
     * @return array
     */
    protected function buildDirty(array $newFields, array $oldFields): array
    {
        $dirty = [];
        foreach (array_intersect_key($newFields, $oldFields) as $uid => $group) {
            $groupIsdirty = $this->buildDirtyGroup($group, $oldFields[$uid]);
            if ($groupIsdirty) {
                $dirty['changed'][$uid] = $groupIsdirty;
            }
        }
        $added = array_diff_key($newFields, $oldFields);
        if ($added) {
            foreach ($added as $uid => $group) {
                foreach ($group['blockTypes'] as $tid => $type) {
                    if (isset($type['layoutConfig'])) {
                        $added[$uid]['blockTypes'][$tid]['layoutConfig'] = ['added' => $type['layoutConfig']->value];
                    }
                }
            }
            $dirty['added'] = $added;
        }
        $removed = array_diff_key($oldFields, $newFields);
        if ($removed) {
            foreach ($removed as $uid => $group) {
                foreach ($group['blockTypes'] as $tid => $type) {
                    if (isset($type['layoutConfig'])) {
                        $removed[$uid]['blockTypes'][$tid]['layoutConfig'] = ['removed' => $type['layoutConfig']->value];
                    }
                }
            }
            $dirty['removed'] = $removed;
        }
        return $dirty;
    }

    /**
     * Build dirty group values
     *
     * @param  array  $newGroup
     * @param  array  $oldGroup
     * @return array
     */
    protected function buildDirtyGroup(array $newGroup, array $oldGroup): array
    {
        $dirty = [];
        if ($newGroup['name'] != $oldGroup['name']) {
            $dirty['name'] = [
                'f' => $oldGroup['name'],
                't' => $newGroup['name'],
            ];
        }
        foreach (array_intersect_key($newGroup['blockTypes'], $oldGroup['blockTypes']) as $uid => $type) {
            $typeIsdirty = $this->buildDirtyType($type, $oldGroup['blockTypes'][$uid]);
            if ($typeIsdirty) {
                $dirty['blockTypes']['changed'][$uid] = $typeIsdirty;
            }
        }
        $added = array_diff_key($newGroup['blockTypes'], $oldGroup['blockTypes']);
        if ($added) {
            foreach ($added as $uid => $type) {
                if (isset($type['layoutConfig'])) {
                    $added[$uid]['layoutConfig'] = ['added' => $type['layoutConfig']->value];
                }
            }
            $dirty['blockTypes']['added'] = $added;
        }
        $removed = array_diff_key($oldGroup['blockTypes'], $newGroup['blockTypes']);
        if ($removed) {
            foreach ($removed as $uid => $type) {
                if (isset($type['layoutConfig'])) {
                    $removed[$uid]['layoutConfig'] = ['removed' => $type['layoutConfig']->value];
                }
            }
            $dirty['blockTypes']['removed'] = $removed;
        }
        if ($dirty and !isset($dirty['name'])) {
            $dirty['name'] = $newGroup['name'];
        }
        return $dirty;
    }

    /**
     * Build dirty block type values
     *
     * @param  array  $newType
     * @param  array  $oldType
     * @return array
     */
    protected function buildDirtyType(array $newType, array $oldType): array
    {
        $dirty = [];
        foreach (['name', 'handle', 'icon', 'minBlocks', 'maxBlocks', 'template', 'enabled'] as $field) {
            if (($newType[$field] ?? null) !== ($oldType[$field] ?? null)) {
                $dirty[$field] = [
                    'f' => $oldType[$field] ?? null,
                    't' => $newType[$field] ?? null
                ];
            }
        }
        $layoutDirty = false;
        if (!isset($newType['layoutConfig'])) {
            $layoutDirty = isset($oldType['layoutConfig']) ? ['removed' => $oldType['layoutConfig']->value] : false;
        } elseif (!isset($oldType['layoutConfig'])) {
            $layoutDirty = isset($newType['layoutConfig']) ? ['added' => $newType['layoutConfig']->value] : false;
        } else {
            $layoutDirty = $newType['layoutConfig']->getDirty($oldType['layoutConfig']);
        }
        if ($layoutDirty) {
            $dirty['layoutConfig'] = $layoutDirty;
        }
        if ($dirty and !isset($dirty['name'])) {
            $dirty['name'] = $newType['name'];
        }
        return $dirty;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[verbb\\vizy\\fields\\VizyField].fieldData'
        ];
    }

    /**
     * Build values
     *
     * @param  array $config
     * @return array
     */
    protected function buildValues(array $config): array
    {
        $config = ProjectConfigHelper::unpackAssociativeArrays($config);
        $values = [];
        foreach ($config as $group) {
            $value = [
                'name' => $group['name'],
            ];
            foreach ($group['blockTypes'] ?? [] as $type) {
                foreach (['name', 'handle', 'minBlocks', 'maxBlocks', 'template', 'enabled'] as $field) {
                    if (isset($type[$field])) {
                        $value['blockTypes'][$type['id']][$field] = $type[$field];
                    }
                }
                if (isset($type['icon']['label'])) {
                    $value['blockTypes'][$type['id']]['icon'] = $type['icon']['label'];
                }
                if (isset($type['layoutConfig'])) {
                    $value['blockTypes'][$type['id']]['layoutConfig'] = new FieldLayout([
                        'unpacked' => true,
                        'value' => [$type['layoutConfig']]
                    ]);
                }
            }
            $values[$group['id']] = $value;
        }
        return $values;
    }
}
