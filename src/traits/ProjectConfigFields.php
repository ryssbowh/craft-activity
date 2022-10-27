<?php

namespace Ryssbowh\Activity\traits;

use Ryssbowh\Activity\Activity;

trait ProjectConfigFields
{
    /**
     * Get config values for a path
     * 
     * @param  string $basePath
     * @param  array  $config
     * @return array
     */
    public function getConfigValues(string $basePath, array $config): array
    {
        $names = $this->getTrackedFieldNames();
        if ($names == '*') {
            $names = array_keys($config);
        }
        $handlers = [];
        foreach ($names as $baseName) {
            $name = $baseName;
            $path = $basePath . '.' . $name;
            $subs = [];
            if (strpos($name, '.') !== false) {
                $subs = explode('.', $name);
                $name = $subs[0];
                unset($subs[0]);
            }
            if (array_key_exists($name, $config)) {
                $value = $config[$name];
                $skip = false;
                foreach ($subs as $sub) {
                    if (!array_key_exists($sub, $value)) {
                        $skip = true;
                        break;
                    }
                    $value = $value[$sub];
                }
                if (!$skip) {
                    $class = Activity::$plugin->fieldHandlers->getForProjectConfigPath($path);
                    $handlers[$baseName] = new $class([
                        'value' => $this->typeValue($baseName, $value)
                    ]);
                }
            }
        }
        return $handlers;
    }

    /**
     * Calculate dirty config, $newSettigns and $oldSettings are arrays of field handlers
     * 
     * @param  string $basePath
     * @param  array  $newSettings
     * @param  array  $oldSettings
     * @return array
     */
    public function getDirtyConfig(string $basePath, array $newSettings, array $oldSettings): array
    {
        $dirty = [];
        $newSettings = $this->getConfigValues($basePath, $newSettings);
        $oldSettings = $this->getConfigValues($basePath, $oldSettings);
        foreach ($newSettings as $name => $handler) {
            if (!array_key_exists($name, $oldSettings)) {
                $dirty[$name] = [
                    'data' => $handler->getDbValue('t'),
                    'handler' => get_class($handler)
                ];
            } elseif ($handler->isDirty($oldSettings[$name])) {
                $dirty[$name] = [
                    'data' => $handler->getDirty($oldSettings[$name]),
                    'handler' => get_class($handler)
                ];
            }
        }
        foreach (array_diff_key($oldSettings, $newSettings) as $name => $handler) {
            $dirty[$name] = [
                'data' => $handler->getDbValue('f'),
                'handler' => get_class($handler)
            ];
        }
        return $dirty;
    }

    /**
     * Type a value by path
     * 
     * @param  string $path
     * @param  mixed $value
     * @return mixed
     */
    protected function typeValue(string $path, $value): mixed
    {
        $typing = $this->getTrackedFieldTypings()[$path] ?? null;
        switch ($typing) {
            case 'bool':
                return (bool)$value;
            case 'int':
                return (int)$value;
            case 'float':
                return (float)$value;
            case 'string':
                return (string)$value;
        }
        return $value;
    }

    /**
     * Get fields typing, must return an array :
     *
     * [
     *     'field.path' => 'bool'
     * ]
     *
     * Valid typings are 'string', 'int', 'float' and 'bool'
     * 
     * @return array
     */
    protected function getTrackedFieldTypings(): array
    {
        return [];
    }

    /**
     * Get tracked config names, return '*' for all
     * 
     * @return array|string
     */
    abstract protected function getTrackedFieldNames();
}