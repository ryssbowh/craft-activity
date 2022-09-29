<?php

namespace Ryssbowh\Activity\base\fieldHandlers;

abstract class ElementsFieldHandler extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $handle = $this->field->handle;
        $fvalue = $this->field->normalizeValue($this->element->$handle, $this->element);
        $this->fancyValue = array_map(function ($elem) {
            return $elem->title;
        }, is_array($fvalue) ? $fvalue : $fvalue->all());
        if (!$this->fancyValue) {
            $this->value = null;
            $this->fancyValue = null;
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
    public function isDirty(FieldHandler $handler): bool
    {
        $from = is_array($this->value) ? $this->value : [];
        $to = is_array($handler->value) ? $handler->value : [];
        return !(empty(array_diff($from, $to)) and empty(array_diff($to, $from)));
    }
}