<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use craft\fields\Dropdown;
use craft\fields\RadioButtons;

class ListField extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $handle = $this->field->handle;
        if ($this->value) {
            $this->fancyValue = $this->element->$handle->label;
        }
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            Dropdown::class,
            RadioButtons::class,
        ];
    }
}