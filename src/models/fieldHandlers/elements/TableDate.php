<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;

class TableDate extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->value);
        if ($datetime) {
            $this->value = $datetime->format('Y-m-d');
        }
    }
}