<?php

namespace Ryssbowh\Activity\base\fieldHandlers;

use craft\base\Model;

abstract class ConfigFieldHandler extends FieldHandler
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var string
     */
    public $typing;

    /**
     * @var mixed
     */
    public $rawValue;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var mixed
     */
    public $fancyValue;

    /**
     * @inheritDoc
     */
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