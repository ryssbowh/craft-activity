<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\ElementFieldHandler;

class Author extends ElementFieldHandler
{
    public function init()
    {
        parent::init();
        $this->value = $this->rawValue->id;
        $this->fancyValue = $this->rawValue->friendlyName;
    }

    public function hasFancyValue(): bool
    {
        return true;
    }
}