<?php

namespace Ryssbowh\Activity\base\fieldHandlers;

use craft\base\Element;
use craft\base\Field;

abstract class ElementFieldHandler extends FieldHandler
{
    /**
     * @var Element
     */
    public $element;

    /**
     * @var Field
     */
    public $field;

    /**
     * @var string
     */
    public $name;

    /**
     * @var mixed
     */
    public $rawValue;

    /**
     * @inheritDoc
     */
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