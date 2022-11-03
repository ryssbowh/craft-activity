<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use craft\base\Field;
use craft\helpers\ProjectConfig as ProjectConfigHelper;
use craft\services\ProjectConfig;

/**
 * @since 2.2.0
 */
class LinkFieldTypes extends DefaultHandler
{
    /**
     * @var array
     */
    protected $_dirty;

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/link-field-types';
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
     * @return Field
     */
    protected function getField(): Field
    {
        $field = \Craft::$app->fields->createField([
            'type' => 'lenz\\linkfield\\fields\\LinkField'
        ]);
        return $field;
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
        $types = $this->field->getAvailableLinkTypes();
        $dirty = [];

        foreach (array_intersect_key($newFields, $oldFields) as $name => $typeSettings) {
            $fdirty = [
                'label' => $types[$name]->displayName,
                'mode' => 'changed',
                'data' => []
            ];
            $rowDirty = false;
            foreach ($typeSettings as $fieldName => $value) {
                if ($value !== ($oldFields[$name][$fieldName] ?? null)) {
                    $fdirty['data'][$fieldName] = [
                        'f' => $oldFields[$name][$fieldName] ?? null,
                        't' => $value
                    ];
                    if ($fieldName == 'sources') {
                        $fdirty['data'][$fieldName]['ff'] = $this->getFancySources($oldFields[$name][$fieldName] ?? []);
                        $fdirty['data'][$fieldName]['tf'] = $this->getFancySources($value);
                    }
                    if ($fieldName == 'sites') {
                        $fdirty['data'][$fieldName]['ff'] = $this->getFancySites($oldFields[$name][$fieldName] ?? []);
                        $fdirty['data'][$fieldName]['tf'] = $this->getFancySites($value);
                    }
                    $rowDirty = true;
                }
            }
            if ($rowDirty) {
                $dirty[$name] = $fdirty;
            }
        }
        foreach (array_diff_key($newFields, $oldFields) as $name => $typeSettings) {
            $fdirty = [
                'label' => $types[$name]->displayName,
                'mode' => 'added',
                'data' => []
            ];
            $rowDirty = false;
            foreach ($typeSettings as $fieldName => $value) {
                if ($value !== ($oldFields[$name][$fieldName] ?? null)) {
                    $fdirty['data'][$fieldName] = [
                        't' => $value
                    ];
                    if ($fieldName == 'sources') {
                        $fdirty['data'][$fieldName]['tf'] = $this->getFancySources($value);
                    }
                    if ($fieldName == 'sites') {
                        $fdirty['data'][$fieldName]['tf'] = $this->getFancySites($value);
                    }
                    $rowDirty = true;
                }
            }
            if ($rowDirty) {
                $dirty[$name] = $fdirty;
            }
        }
        foreach (array_diff_key($oldFields, $newFields) as $name => $typeSettings) {
            $fdirty = [
                'label' => $types[$name]->displayName,
                'mode' => 'deleted',
                'data' => []
            ];
            $rowDirty = false;
            foreach ($typeSettings as $fieldName => $value) {
                if ($value !== ($newFields[$name][$fieldName] ?? null)) {
                    $fdirty['data'][$fieldName] = [
                        'f' => $value,
                    ];
                    if ($fieldName == 'sources') {
                        $fdirty['data'][$fieldName]['ff'] = $this->getFancySources($value);
                    }
                    if ($fieldName == 'sites') {
                        $fdirty['data'][$fieldName]['tf'] = $this->getFancySites($value);
                    }
                    $rowDirty = true;
                }
            }
            if ($rowDirty) {
                $dirty[$name] = $fdirty;
            }
        }
        return $dirty;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            ProjectConfig::PATH_FIELDS . '.{uid}.settings[lenz\\linkfield\\fields\\LinkField].typeSettings'
        ];
    }

    /**
     * Get fancy sources names
     * 
     * @param  array|string $sources
     * @return string
     */
    protected function getFancySources($sources): ?string
    {
        if ($sources === '*') {
            return \Craft::t('app', 'All');
        }
        $fancy = [];
        foreach ($sources as $source) {
            $elems = explode(':', $source);
            if (sizeof($elems) == 1) {
                $fancy[] = \Craft::t('app', ucfirst($elems[0]));
            } elseif ($elems[0] == 'volume') {
                $model = \Craft::$app->volumes->getVolumeByUid($elems[1] ?? '');
                $fancy[] = $model ? $model->name : $source;
            } elseif ($elems[0] == 'section') {
                $model = \Craft::$app->sections->getSectionByUid($elems[1] ?? '');
                $fancy[] = $model ? $model->name : $source;
            } elseif ($elems[0] == 'group') {
                $model = \Craft::$app->userGroups->getGroupByUid($elems[1] ?? '');
                $fancy[] = $model ? $model->name : $source;
            }
        }
        return implode(', ', $fancy);
    }

    /**
     * Get fancy sites names
     * 
     * @param  array|string $sites
     * @return string
     */
    protected function getFancySites($sites): string
    {
        if ($sites === '*') {
            return \Craft::t('app', 'All');
        }
        $fancy = [];
        foreach ($sites as $id) {
            $model = \Craft::$app->sites->getSiteById($id);
            $fancy[] = $model ? $model->name : $id;
        }
        return implode(', ', $fancy);
    }
}