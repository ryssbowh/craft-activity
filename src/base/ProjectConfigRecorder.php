<?php

namespace Ryssbowh\Activity\base;

use Ryssbowh\Activity\traits\ProjectConfigFields;

abstract class ProjectConfigRecorder extends Recorder
{
    use ProjectConfigFields;

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
        $this->saveLog($type, [
            'changedFields' => $dirty
        ]);
    }

    protected function getObfuscatedSettings(): array
    {
        return [];
    }

    protected function obfuscateDirtySettings(array $dirty): array
    {
        return $dirty;
    }
}