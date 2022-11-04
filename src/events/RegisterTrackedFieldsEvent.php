<?php

namespace Ryssbowh\Activity\events;

use yii\base\Event;

class RegisterTrackedFieldsEvent extends Event
{
    /**
     * @var array|string
     */
    public $tracked;
}