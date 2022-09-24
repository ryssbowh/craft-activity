<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;

class Unknown extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public function isDirty(FieldHandler $handler): bool
    {
        return false;
    }
}