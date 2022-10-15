<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\ProjectConfig;

class FsType extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->fancyValue = $this->value::displayName();
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
            ProjectConfig::PATH_FS . '.{uid}.type'
        ];
    }
}