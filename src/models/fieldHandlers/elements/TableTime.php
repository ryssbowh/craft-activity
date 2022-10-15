<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;

class TableTime extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->value);
        if ($datetime) {
            $this->value = $datetime->format('H:i');
        }
    }
}