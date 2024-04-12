<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;

class Authors extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        $this->value = array_map(function ($user) {
            return $user->id;
        }, $this->rawValue);
        $this->fancyValue = array_map(function ($user) {
            return $user->friendlyName;
        }, $this->rawValue);
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function getTemplate(): ?string
    {
        return 'activity/field-handlers/authors-field';
    }
}
