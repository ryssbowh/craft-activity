<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\helpers\ProjectConfig as ProjectConfigHelper;
use craft\services\ProjectConfig;

class TableColumns extends DefaultHandler
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
        return 'activity/field-handlers/table-columns';
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
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[craft\\fields\\Table].columns',
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
        $dirty = [];
        foreach ($newFields as $id => $values) {
            if (array_key_exists($id, $oldFields)) {
                $rowDirty = false;
                $rDirty = [
                    'mode' => 'changed',
                    'row' => $id
                ];
                foreach (['heading', 'handle', 'width', 'type'] as $field) {
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
                    'heading' => ['t' => $values['heading']],
                    'handle' => ['t' => $values['handle']],
                    'width' => ['t' => $values['width']],
                    'type' => ['t' => $values['type']]
                ];
            }
        }
        foreach (array_diff_key($oldFields, $newFields) as $id => $values) {
            $dirty[] = [
                'row' => $id,
                'mode' => 'removed',
                'heading' => ['f' => $values['heading']],
                'handle' => ['f' => $values['handle']],
                'width' => ['f' => $values['width']],
                'type' => ['f' => $values['type']]
            ];
        }
        return $dirty;
    }
}