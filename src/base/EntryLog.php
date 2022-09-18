<?php

namespace Ryssbowh\Activity\base;

use craft\elements\Entry;

abstract class EntryLog extends ElementLog
{
    /**
     * @inheritDoc
     */
    protected function getElementType(): string
    {
        return Entry::class;
    }
}