<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use Ryssbowh\Activity\traits\ProjectConfigFields;
use craft\fieldlayoutelements\CustomField;
use craft\services\ProjectConfig;

class BlockFields extends DefaultHandler
{
    use ProjectConfigFields;

    /**
     * @var array
     */
    protected $_dirty;

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/block-field-layout';
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
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_MATRIX_BLOCK_TYPES . '.{uid}.fields'
        ];
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
        foreach (array_intersect_key($newFields, $oldFields) as $uid => $config) {
            $fdirty = $this->getDirtyConfig(ProjectConfig::PATH_FIELDS . '.{uid}', $config, $oldFields[$uid]);
            if ($fdirty) {
                $dirty['changed'][] = [
                    'type' => $config['type'],
                    'name' => $config['name'],
                    'data' => $fdirty
                ];
            }
        }
        foreach (array_diff_key($newFields, $oldFields) as $uid => $config) {
            $fdirty = $this->getDirtyConfig(ProjectConfig::PATH_FIELDS . '.{uid}', $config, []);
            if ($fdirty) {
                $dirty['added'][] = [
                    'type' => $config['type'],
                    'name' => $config['name'],
                    'data' => $fdirty
                ];
            }
        }
        foreach (array_diff_key($oldFields, $newFields) as $uid => $config) {
            $fdirty = $this->getDirtyConfig(ProjectConfig::PATH_FIELDS . '.{uid}', [], $config);
            if ($fdirty) {
                $dirty['removed'][] = [
                    'type' => $config['type'],
                    'name' => $config['name'],
                    'data' => $fdirty
                ];
            }
        }
        return $dirty;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldTypings(array $config): array
    {
        return Activity::$plugin->fields->getTrackedFieldTypings($config['type']);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(array $config)
    {
        if (!isset($config['type'])) {
            $tracked = Activity::$plugin->fields->getTrackedFieldNames('_base');
        } else {
            $tracked = array_merge(Activity::$plugin->fields->getTrackedFieldNames($config['type']), ['required', 'width']);
        }
        if (($key = array_search('fieldGroup', $tracked)) !== false) {
            unset($tracked[$key]);
        }
        return $tracked;
    }

    /**
     * @inheritDoc
     */
    protected function getPathFieldHandler(string $path, array $config): string
    {
        $path = explode('.', $path);
        if ($path[2] == 'settings') {
            $path[2] = 'settings[' . $config['type'] .']';
        }
        $path = implode('.', $path);
        return Activity::$plugin->fieldHandlers->getForProjectConfigPath($path);
    }
}