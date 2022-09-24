<?php 

namespace Ryssbowh\Activity\models;

use craft\base\Model;

class ChangedField extends Model
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $data;

    /**
     * Does this field define a from value
     * 
     * @return boolean
     */
    public function getHasFrom(): bool
    {
        return array_key_exists('f', $this->data);
    }

    /**
     * Does this field define a to value
     * 
     * @return boolean
     */
    public function getHasTo(): bool
    {
        return array_key_exists('t', $this->data);
    }

    /**
     * Get field from value
     * 
     * @return mixed|null
     */
    public function getFrom()
    {
        return $this->data['f'] ?? null;
    }

    /**
     * Get field to value
     * 
     * @return mixed|null
     */
    public function getTo()
    {
        return $this->data['t'] ?? null;
    }

    /**
     * Does this field define a fancy from value
     * 
     * @return boolean
     */
    public function getHasFancyFrom(): bool
    {
        return array_key_exists('ff', $this->data);
    }

    /**
     * Does this field define a fancy to value
     * 
     * @return boolean
     */
    public function getHasFancyTo(): bool
    {
        return array_key_exists('tf', $this->data);
    }

    /**
     * Get field fancy from value
     * 
     * @return mixed|null
     */
    public function getFancyFrom()
    {
        return $this->data['ff'] ?? null;
    }

    /**
     * Get field fancy to value
     * 
     * @return mixed|null
     */
    public function getFancyTo()
    {
        return $this->data['tf'] ?? null;
    }
}