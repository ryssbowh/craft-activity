<?php

namespace Ryssbowh\Activity\base\logs;

use craft\elements\Category;

abstract class CategoryLog extends ElementLog
{
    /**
     * @inheritDoc
     */
    protected function getElementType(): string
    {
        return Category::class;
    }
}