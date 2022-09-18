<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\ElementFieldHandler;
use craft\fields\Date as DateField;

class Date extends ElementFieldHandler
{
    public $format;

    public function init()
    {
        parent::init();
        $this->format = $this->format ?? 'Y-m-d H:i:s';
        if ($this->field) {
            if (!$this->field->showTime) {
                $this->format = 'Y-m-d';
            }
        }
        if ($this->rawValue) {
            $this->value = $this->rawValue->format($this->format);
        }
    }

    public static function getTargets(): array
    {
        return [
            DateField::class,
        ];
    }
}