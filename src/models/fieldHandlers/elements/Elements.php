<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\ElementFieldHandler;
use Ryssbowh\Activity\base\FieldHandler;
use craft\elements\User;
use craft\fields\Assets;
use craft\fields\Categories;
use craft\fields\Entries;
use craft\fields\Tags;
use craft\fields\Users;

class Elements extends ElementFieldHandler
{
    public function init()
    {
        parent::init();
        $handle = $this->field->handle;
        $fvalue = $this->field->normalizeValue($this->element->$handle, $this->element);
        $this->fancyValue = array_map(function ($elem) {
            return ($elem instanceof User) ? $elem->friendlyName : $elem->title;
        }, is_array($fvalue) ? $fvalue : $fvalue->all());
        if (!$this->fancyValue) {
            $this->value = null;
            $this->fancyValue = null;
        }
    }

    public function hasFancyValue(): bool
    {
        return true;
    }

    public function isDirty(FieldHandler $handler): bool
    {
        $from = is_array($this->value) ? $this->value : [];
        $to = is_array($handler->value) ? $handler->value : [];
        return !(empty(array_diff($from, $to)) and empty(array_diff($to, $from)));
    }

    public static function getTargets(): array
    {
        return [
            Entries::class,
            Categories::class,
            Assets::class,
            Tags::class,
            Users::class,
            'craft\\commerce\\fields\\Products',
            'craft\\commerce\\fields\\Variants'
        ];
    }
}