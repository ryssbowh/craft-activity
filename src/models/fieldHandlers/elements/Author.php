<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;

class Author extends ElementFieldHandler
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        parent::init();
        $this->value = $this->rawValue->id;
        $this->fancyValue = $this->rawValue->friendlyName;
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
        return 'activity/field-handlers/author-field';
    }
}