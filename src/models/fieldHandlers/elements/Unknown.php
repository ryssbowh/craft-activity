<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\ElementFieldHandler;
use Ryssbowh\Activity\base\FieldHandler;

class Unknown extends ElementFieldHandler
{
    public function isDirty(FieldHandler $handler): bool
    {
        return false;
    }
}