<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\Activity;
use craft\base\Model;

trait ConfigModelFields
{
    /**
     * Get the tracked field values for a model
     * 
     * @param  Model  $model
     * @return array
     */
    protected function getTrackedFieldValues(Model $model): array
    {
        $handlers = [];
        $typings = $this->getTrackedFieldTypings();
        foreach ($this->getTrackedFieldNames($model) as $name) {
            $class = Activity::$plugin->fieldHandlers->getForConfigField(get_class($model), $name);
            $handlers[$name] = new $class([
                'model' => $model,
                'typing' => $typings[$name] ?? null,
                'name' => $name
            ]);
        }
        return $handlers;
    }

    /**
     * Get the dirty fields
     * 
     * @param  array $newFields
     * @param  array $oldFields
     * @return array
     */
    protected function getDirtyFields(array $newFields, array $oldFields): array
    {
        $dirty = [];
        foreach ($newFields as $name => $handler) {
            if (!array_key_exists($name, $oldFields)) {
                $dirty[$name] = $handler->getDbValue('t');
            } elseif ($handler->isDirty($oldFields[$name])) {
                $dirty[$name] = $handler->getDirty($oldFields[$name]);
            }
        }
        foreach (array_diff_key($oldFields, $newFields) as $name => $handler) {
            $dirty[$name] = $handler->getDbValue('f');
        }
        return $dirty;
    }

    /**
     * Get the tracked fields typings, used to transform the value before saving
     * 
     * @return array
     */
    protected function getTrackedFieldTypings(): array
    {
        return [];
    }

    abstract protected function getTrackedFieldNames(Model $model): array;
}