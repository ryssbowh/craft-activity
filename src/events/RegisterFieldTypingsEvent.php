<?php

namespace Ryssbowh\Activity\events;

use yii\base\Event;

class RegisterFieldTypingsEvent extends Event
{
    /**
     * @var array|string
     */
    public $typings;
}