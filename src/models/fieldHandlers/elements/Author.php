<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;

class Author extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->value = $this->rawValue->id;
        $this->fancyValue = $this->rawValue->friendlyName;
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }
}