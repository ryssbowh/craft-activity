<?php

namespace Ryssbowh\Activity\models\fieldHandlers\projectConfig;

use craft\services\Volumes;

class VolumeType extends DefaultHandler
{
    /**
     * @inheritDoc
     */
    public function init()
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
    protected static function _getTargets(): array
    {
        return [
            Volumes::CONFIG_VOLUME_KEY . '.{uid}.type'
        ];
    }
}