<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\ElementFieldHandler;
use Ryssbowh\Activity\base\FieldHandler;
use craft\fields\Checkboxes;
use craft\fields\MultiSelect;

class ListsField extends ElementFieldHandler
{
    public $fancyValue;

    public function init()
    {
        parent::init();
        $handle = $this->field->handle;
        $this->fancyValue = array_map(function ($elem) {
            return $elem->label;
        }, array_filter((array)$this->element->$handle, function ($elem) {
            return $elem->selected;
        }));
        if (!$this->fancyValue) {
            $this->value = null;
            $this->fancyValue = null;
        }
    }

    public function isDirty(FieldHandler $handler): bool
    {
        $from = is_array($this->value) ? $this->value : [];
        $to = is_array($handler->value) ? $handler->value : [];
        return !(empty(array_diff($from, $to)) and empty(array_diff($to, $from)));
    }

    public function hasFancyValue(): bool
    {
        return true;
    }

    public static function getTargets(): array
    {
        return [
            Checkboxes::class,
            MultiSelect::class
        ];
    }
}