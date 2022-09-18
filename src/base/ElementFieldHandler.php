<?php

namespace Ryssbowh\Activity\base;

abstract class ElementFieldHandler extends FieldHandler
{
    public $element;
    public $field;
    public $name;
    public $rawValue;

    public function getDbValue(string $valueKey): array
    {
        $data = [
            $valueKey => $this->value,
            'name' => $this->name
        ];
        if ($this->hasFancyValue()) {
            $data[$valueKey . 'f'] = $this->fancyValue;
        }
        return $data;
    }
}