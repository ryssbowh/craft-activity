<?php

namespace Ryssbowh\Activity\base;

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