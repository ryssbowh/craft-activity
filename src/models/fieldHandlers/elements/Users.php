<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementsFieldHandler;
use craft\fields\Users as UsersField;

class Users extends ElementsFieldHandler
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $handle = $this->field->handle;
        $fvalue = $this->field->normalizeValue($this->element->getFieldValue($handle), $this->element);
        $this->fancyValue = array_map(function ($elem) {
            return $elem->friendlyName;
        }, is_array($fvalue) ? $fvalue : $fvalue->all());
        if (!$this->fancyValue) {
            $this->value = null;
            $this->fancyValue = null;
        }
    }

    /**
     * @inheritDoc
     */
    protected static function _getTargets(): array
    {
        return [
            UsersField::class
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/users-field';
    }
}
