<?php

namespace Ryssbowh\Activity\base\logs;

use craft\elements\Asset;

abstract class AssetLog extends ElementLog
{
    /**
     * @inheritDoc
     */
    protected function getElementType(): string
    {
        return Asset::class;
    }
}