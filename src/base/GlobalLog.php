<?php

namespace Ryssbowh\Activity\base;

use craft\base\Element;
use craft\elements\GlobalSet;

abstract class GlobalLog extends ElementLog
{
    /**
     * @inheritDoc
     */
    protected function getElementType(): string
    {
        return GlobalSet::class;
    }

    /**
     * @inheritDoc
     */
    protected function getTitleField(): string
    {
        return 'name';
    }

    /**
     * @inheritDoc
     */
    protected function getIncludeSiteNameInTitle(): bool
    {
        return false;
    }
}