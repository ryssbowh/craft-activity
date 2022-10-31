<?php

namespace Ryssbowh\Activity\events;

use yii\base\Event;

class RegisterHandlerTargetsEvent extends Event
{
    /**
     * @var array
     */
    public $targets;
}