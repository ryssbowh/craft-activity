<?php

namespace Ryssbowh\Activity\base;

abstract class ConfigFieldHandler extends FieldHandler
{
    public $name;
    public $model;
    public $typing;
    public $rawValue;
    public $value;
    public $fancyValue;

    public function init()
    {
        parent::init();
        if ($this->model) {
            try {
                if (strpos($this->name, '.') !== false) {
                    $elems = explode('.', $this->name);
                    $value = $this->model;
                    foreach ($elems as $elem) {
                        $value = $value->{$elem};
                    }
                } else {
                    $value = $this->model->{$this->name};
                }
                if ($this->typing) {
                    settype($value, $this->typing);
                }
            } catch (\Exception $e) {
                $value = null;
            }
            $this->rawValue = $this->value = $value;
        }
    }
}