<?php

namespace Ryssbowh\Activity\events;

use yii\base\Event;

class RegisterFieldTypingsEvent extends Event
{
    /**
     * @var array|string
     */
    public $typings;

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->typings = [
            '_base' => ['searchable' => 'bool']
        ];
    }
}