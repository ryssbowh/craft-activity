<?php

namespace Ryssbowh\Activity\models\fieldHandlers\config;

use craft\base\Field;

class FieldType extends DefaultHandler
{
    public function init()
    {
        parent::init();
        $this->rawValue = $this->value = get_class($this->model);
        $this->fancyValue = $this->getTypes()[$this->rawValue];
    }

    public static function getTargets(): array
    {
        return array_map(function ($val) {
            return 'type';
        }, static::getTypes());
    }

    public function hasFancyValue(): bool
    {
        return true;
    }

    protected static function getTypes(): array
    {
        $types = [];
        foreach (\Craft::$app->fields->getAllFieldTypes() as $class) {
            $types[$class] = $class::displayName();
        }
        return $types;
    }
}