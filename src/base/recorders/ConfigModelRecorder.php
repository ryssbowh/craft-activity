<?php

namespace Ryssbowh\Activity\base\recorders;

use Ryssbowh\Activity\Activity;
use craft\events\ConfigEvent;
use yii\base\Event;

abstract class ConfigModelRecorder extends ProjectConfigRecorder
{
    /**
     * Saved a log on update
     * 
     * @param ConfigEvent $event
     */
    public function onUpdate(ConfigEvent $event)
    {
        $type = $this->getActivityHandle() . 'Saved';
        $this->onChanged($event, $type);
    }

    /**
     * Saved a log on add
     * 
     * @param ConfigEvent $event
     */
    public function onAdd(ConfigEvent $event)
    {
        $type = $this->getActivityHandle() . 'Created';
        $this->onChanged($event, $type);
    }

    /**
     * Saved a log on remove
     * 
     * @param ConfigEvent $event
     */
    public function onRemove(ConfigEvent $event)
    {
        $type = $this->getActivityHandle() . 'Deleted';
        $this->onChanged($event, $type);
    }

    /**
     * Saves a log when config is changed
     * 
     * @param ConfigEvent $event
     * @param string      $type
     */
    protected function onChanged(ConfigEvent $event, string $type)
    {
        if (!$this->shouldSaveLog($type)) {
            return;
        }
        $path = $event->path;
        if ($event->tokenMatches) {
            $path = str_replace($event->tokenMatches[0], '{uid}', $path);
        }
        $params = [
            'target_uid' => $event->tokenMatches[0] ?? null
        ];
        if ($field = $this->getDescriptiveFieldName()) {
            $params['target_name'] = $event->newValue[$field] ?? $event->oldValue[$field] ?? '';
        }
        if (Activity::$plugin->settings->trackConfigFieldsChanges) {
            $changed = $this->getDirtyConfig($path, $event->newValue ?? [], $event->oldValue ?? []);
            if (Activity::$plugin->settings->ignoreNoConfigChanges and !$changed) {
                return;
            }
            $params['changedFields'] = $changed;
        }
        $params = $this->modifyParams($params, $event);
        $this->commitLog($type, $params);
    }

    /**
     * Get the config field name that describes what's being changed
     * 
     * @return ?string
     */
    protected function getDescriptiveFieldName(): ?string
    {
        return null;
    }

    /**
     * Modify log param before it's saved
     * 
     * @param  array       $params
     * @param  ConfigEvent $event
     * @return array
     */
    protected function modifyParams(array $params, ConfigEvent $event): array
    {
        return $params;
    }

    /**
     * Get the activity handle, used to build the log type
     * 
     * @return string
     */
    abstract protected function getActivityHandle(): string;
}