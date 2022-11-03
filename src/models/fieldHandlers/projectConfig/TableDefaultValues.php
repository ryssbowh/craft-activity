<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\base\Field;
use craft\helpers\ProjectConfig as ProjectConfigHelper;
use craft\services\ProjectConfig;

/**
 * @since 2.2.0
 */
class TableDefaultValues extends DefaultHandler
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
        $this->value = array_values(ProjectConfigHelper::unpackAssociativeArray($this->value));
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/table-default-values';
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
     * Get the Craft field
     * 
     * @return ?Field
     */
    protected function getField(): ?Field
    {
        return \Craft::$app->fields->getFieldByUid($this->data['fieldUid']);
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\fields\\Table].defaults',
        ];
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
        if (!$this->field) {
            return [];
        }
        $dirty = [];
        $columns = $this->field->columns;
        foreach ($newFields as $id => $values) {
            if (array_key_exists($id, $oldFields)) {
                $rowDirty = false;
                $rDirty = [
                    'mode' => 'changed',
                    'row' => $id
                ];
                foreach ($columns as $col => $data) {
                    if (($values[$col] ?? null) !== ($oldFields[$id][$col] ?? null)) {
                        $rowDirty = true;
                        $rDirty['cols'][$col] = [
                            'f' => $oldFields[$id][$col] ?? '',
                            't' => $values[$col],
                            'heading' => $data['heading']
                        ];
                    }
                }
                if ($rowDirty) {
                    $dirty[] = $rDirty;
                }
            } else {
                $rDirty = [
                    'row' => $id,
                    'mode' => 'added'
                ];
                foreach ($columns as $col => $data) {
                    $rDirty['cols'][$col] = [
                        't' => $values[$col] ?? '',
                        'heading' => $data['heading']
                    ];
                }
                $dirty[] = $rDirty;
            }
        }
        foreach (array_diff_key($oldFields, $newFields) as $id => $values) {
            $rDirty = [
                'row' => $id,
                'mode' => 'removed'
            ];
            foreach ($columns as $col => $data) {
                $rDirty['cols'][$col] = [
                    'f' => $values[$col] ?? '',
                    'heading' => $data['heading']
                ];
            }
            $dirty[] = $rDirty;
        }
        return $dirty;
    }
}