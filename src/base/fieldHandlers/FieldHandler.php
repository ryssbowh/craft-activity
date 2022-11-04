<?php

namespace Ryssbowh\Activity\base\fieldHandlers;

use Ryssbowh\Activity\Activity;
use craft\base\Model;

abstract class FieldHandler extends Model
{
    const EVENT_REGISTER_TARGETS = 'register-targets';
    
    /**
     * @var mixed
     */
    public $value;

    /**
     * @var mixed
     */
    public $fancyValue;

    /**
     * @var mixed
     */
    public $data;

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
     * Get the targets this field handler applies to, can be modified through an event
     * 
     * @return array
     */
    public static function getTargets(): array
    {
        return Activity::$plugin->fieldHandlers->getHandlerTargets(get_called_class(), static::_getTargets());
    }

    /**
     * Get the targets this field handler applies to
     * 
     * @return array
     */
    protected static function _getTargets(): array
    {
        return [];
    }

    /**
     * Get the template used to render this field description
     * 
     * @return ?string
     */
    public static function getTemplate(): ?string
    {
        return null;
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