<?php

namespace Ryssbowh\Activity\base\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\events\RegisterFieldTypingsEvent;
use Ryssbowh\Activity\events\RegisterTrackedFieldsEvent;
use Ryssbowh\Activity\traits\ProjectConfigFields;
use yii\base\Event;

abstract class ProjectConfigRecorder extends Recorder
{
    use ProjectConfigFields;

    const EVENT_REGISTER_TRACKED_FIELDS = 'event-register-tracked-fields';
    const EVENT_REGISTER_FIELD_TYPINGS = 'event-register-field-typings';

    /**
     * @var array|string
     */
    protected $_trackedFields;

    /**
     * @var array
     */
    protected $_fieldTypings;

    /**
     * Saves a log when some config is changed
     * 
     * @param  string $basePath
     * @param  string $type
     * @param  array  $oldValue
     * @param  array  $newValue
     */
    public function onChanged(string $basePath, string $type, array $oldValue, array $newValue)
    {
        if (!$this->shouldSaveLog($type)) {
            return;
        }
        $dirty = $this->getDirtyConfig($basePath, $newValue, $oldValue);
        $dirty = $this->obfuscateDirtySettings($dirty);
        if (!$dirty) {
            return;
        }
        $this->commitLog($type, [
            'changedFields' => $dirty
        ]);
    }

    /**
     * Get the setting paths that needs to be obfuscated
     * 
     * @return array
     */
    protected function getObfuscatedSettings(): array
    {
        return [];
    }

    /**
     * Obfuscate dirty settings
     * 
     * @param  array $dirty
     * @return array
     */
    protected function obfuscateDirtySettings(array $dirty): array
    {
        foreach ($this->getObfuscatedSettings() as $path) {
            if ($dirty[$path]['f'] ?? false) {
                $dirty[$path]['f'] = '******';
            }
            if ($dirty[$path]['t'] ?? false) {
                $dirty[$path]['t'] = '******';
            }
        }
        return $dirty;
    }

    /**
     * Get tracked config names, which can be modified through an event
     *
     * @param  array $config
     * @return array|string
     */
    protected function getTrackedFieldNames(array $config)
    {
        if ($this->_trackedFields === null) {
            $event = new RegisterTrackedFieldsEvent([
                'tracked' => $this->_getTrackedFieldNames()
            ]);
            Event::trigger($this, self::EVENT_REGISTER_TRACKED_FIELDS, $event);
            $this->_trackedFields = $event->tracked;
        }
        return $this->_trackedFields;
    }

    /**
     * Get fields typing, which can be modified through an event
     *
     * @param  array $config
     * @return array
     */
    protected function getTrackedFieldTypings(array $config): array
    {
        if ($this->_fieldTypings === null) {
            $event = new RegisterFieldTypingsEvent([
                'typings' => $this->_getTrackedFieldTypings()
            ]);
            Event::trigger($this, self::EVENT_REGISTER_FIELD_TYPINGS, $event);
            $this->_fieldTypings = $event->typings;
        }
        return $this->_fieldTypings;
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
    protected function _getTrackedFieldTypings(): array
    {
        return [];
    }

    /**
     * Get tracked config names, return '*' for all
     *
     * @return array|string
     */
    abstract protected function _getTrackedFieldNames();
}