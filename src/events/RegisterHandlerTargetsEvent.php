<?php

namespace Ryssbowh\Activity\events;

use yii\base\Event;

/**
 * @since 2.2.0
 */
class RegisterHandlerTargetsEvent extends Event
{
    /**
     * @var array
     */
    public $targets;
}