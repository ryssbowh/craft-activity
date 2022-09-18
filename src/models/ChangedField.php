<?php 

namespace Ryssbowh\Activity\models;

use craft\base\Model;

class ChangedField extends Model
{
    public $name;
    public $data;

    public function getHasFrom(): bool
    {
        return array_key_exists('f', $this->data);
    }

    public function getHasTo(): bool
    {
        return array_key_exists('t', $this->data);
    }

    public function getFrom()
    {
        return $this->data['f'] ?? null;
    }

    public function getTo()
    {
        return $this->data['t'] ?? null;
    }

    public function getHasFancyFrom(): bool
    {
        return array_key_exists('ff', $this->data);
    }

    public function getHasFancyTo(): bool
    {
        return array_key_exists('tf', $this->data);
    }

    public function getFancyFrom()
    {
        return $this->data['ff'] ?? null;
    }

    public function getFancyTo()
    {
        return $this->data['tf'] ?? null;
    }
}