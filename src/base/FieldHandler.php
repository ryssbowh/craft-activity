<?php

namespace Ryssbowh\Activity\base;

use craft\base\Model;

abstract class FieldHandler extends Model
{
    public $value;
    public $fancyValue;

    /**
     * Does this handler defines a fancy value
     * 
     * @return boolean
     */
    public function hasFancyValue(): bool
    {
        return false;
    }

    /**
     * Get dirty values compared to another field handler
     * 
     * @param FieldHandler $handler
     * @return array
     */
    public function getDirty(FieldHandler $handler): array
    {
        if (!$this->isDirty($handler)) {
            return [];
        }
        return array_merge($this->getDbValue('t'), $handler->getDbValue('f'));
    }

    /**
     * Is the value dirty compared to another field handler
     * 
     * @param  FieldHandler $handler
     * @return boolean
     */
    public function isDirty(FieldHandler $handler): bool
    {
        return $this->value !== $handler->value;
    }

    /**
     * Get the targets this field handler applies to
     * 
     * @return array
     */
    public static function getTargets(): array
    {
        return [];
    }

    /**
     * Get the value to be stored in database.
     * $valueKey is either 'f' for a from value, or 't' for a to value
     * If this handler has a fancy value, the db value will also contain 'ff' or 'tf'
     * 
     * @param string $valueKey
     * @return array
     */
    public function getDbValue(string $valueKey): array
    {
        $data = [
            $valueKey => $this->value
        ];
        if ($this->hasFancyValue()) {
            $data[$valueKey . 'f'] = $this->fancyValue;
        }
        return $data;
    }
}