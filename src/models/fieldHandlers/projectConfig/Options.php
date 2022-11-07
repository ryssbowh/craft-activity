<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\helpers\ProjectConfig as ProjectConfigHelper;
use craft\services\Fields;

/**
 * @since 1.2.0
 */
class Options extends DefaultHandler
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
        return 'activity/field-handlers/field-options';
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
            Fields::CONFIG_FIELDS_KEY . '.{uid}.settings[craft\\fields\\Dropdown].options',
            Fields::CONFIG_FIELDS_KEY . '.{uid}.settings[craft\\fields\\Checkboxes].options',
            Fields::CONFIG_FIELDS_KEY . '.{uid}.settings[craft\\fields\\MultiSelect].options',
            Fields::CONFIG_FIELDS_KEY . '.{uid}.settings[craft\\fields\\RadioButtons].options',
        ];
    }

    /**
     * Build dirty values
     * 
     * @param  array  $newFields
     * @param  array  $oldFields
     * @return array
     */
    protected function buildValues(array $values): array
    {
        $values = ProjectConfigHelper::unpackAssociativeArrays($values);
        foreach ($values as $index => $sub) {
            if (isset($sub['default'])) {
                $values[$index]['default'] = (bool)$sub['default'];
            }
        }
        return $values;
    }

    /**
     * Build dirty values
     * 
     * @param  array $newFields
     * @param  array $oldFields
     * @return array
     */
    protected function buildDirty(array $newFields, array $oldFields): array
    {
        $dirty = [];
        foreach ($newFields as $id => $values) {
            if (array_key_exists($id, $oldFields)) {
                $rowDirty = false;
                $rDirty = [
                    'mode' => 'changed',
                    'row' => $id
                ];
                foreach (['label', 'value', 'default'] as $field) {
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
                    'row' => $id,
                    'mode' => 'added',
                    'label' => ['t' => $values['label']],
                    'value' => ['t' => $values['value']],
                    'default' => ['t' => $values['default']]
                ];
            }
        }
        foreach (array_diff_key($oldFields, $newFields) as $id => $values) {
            $dirty[] = [
                'row' => $id,
                'mode' => 'removed',
                'label' => ['f' => $values['label']],
                'value' => ['f' => $values['value']],
                'default' => ['f' => $values['default']]
            ];
        }
        return $dirty;
    }
}