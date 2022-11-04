<?php

namespace Ryssbowh\Activity\base\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\traits\ProjectConfigFields;
use yii\base\Event;

abstract class ProjectConfigRecorder extends Recorder
{
    use ProjectConfigFields;

    /**
     * Saves a log when some config is changed
     * 
     * @param  string $basePath
     * @param  string $type
     * @param  array  $oldValue
     * @param  array  $newValue
     */
    public function onConfigChanged(string $basePath, string $type, array $oldValue, array $newValue)
    {
        if (!$this->shouldSaveLog($type)) {
            return;
        }
        $dirty = $this->getDirtyConfig($basePath, $newValue, $oldValue);
        if (Activity::$plugin->settings->ignoreNoSettingsChanges and !$dirty) {
            return;
        }
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
}