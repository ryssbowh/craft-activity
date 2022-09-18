<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\ElementFieldHandler;
use craft\fields\Dropdown;
use craft\fields\RadioButtons;

class ListField extends ElementFieldHandler
{
    public $fancyValue;

    public function init()
    {
        parent::init();
        $handle = $this->field->handle;
        if ($this->value) {
            $this->fancyValue = $this->element->$handle->label;
        }
    }

    public function hasFancyValue(): bool
    {
        return true;
    }

    public static function getTargets(): array
    {
        return [
            Dropdown::class,
            RadioButtons::class,
        ];
    }
}