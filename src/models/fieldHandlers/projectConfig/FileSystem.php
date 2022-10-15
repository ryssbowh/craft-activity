<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class FileSystem extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if ($this->value) {
            $this->fancyValue = $this->getFsName($this->value);
        }
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            ProjectConfig::PATH_VOLUMES . '.{uid}.fs',
            ProjectConfig::PATH_VOLUMES . '.{uid}.transformFs'
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/file-system';
    }

    /**
     * Get a file system by handle
     * 
     * @param  string $handle
     * @return string
     */
    protected function getFsName(string $handle): string
    {
        $fs = \Craft::$app->fs->getFilesystemByHandle($handle);
        return $fs ? $fs->name : '';
    }
}