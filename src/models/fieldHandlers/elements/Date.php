<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;
use craft\fields\Date as DateField;
use craft\fields\Time;

class Date extends ElementFieldHandler
{
    /**
     * @var string
     */
    public $format;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        $this->format = $this->format ?? 'Y-m-d H:i:s';
        if ($this->field) {
            if ($this->field instanceof Time) {
                $this->format = 'H:i';
            } else if (!$this->field->showTime) {
                $this->format = 'Y-m-d';
            }
        }
        if ($this->rawValue) {
            $this->value = $this->rawValue->format($this->format);
        }
    }

    /**
     * @inheritDoc
     */
    public static function getTargets(): array
    {
        return [
            DateField::class,
            Time::class
        ];
    }
}