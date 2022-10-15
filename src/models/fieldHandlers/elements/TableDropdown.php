<?php

namespace Ryssbowh\Activity\models\fieldHandlers\elements;

use Ryssbowh\Activity\base\fieldHandlers\ElementFieldHandler;

class TableDropdown extends ElementFieldHandler
{
    /**
     * @var array
     */
    public $options;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        foreach  ($this->options as $option) {
            if ($option['value'] == $this->value) {
                $this->fancyValue = $option['label'];
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function hasFancyValue(): bool
    {
        return true;
    }
}