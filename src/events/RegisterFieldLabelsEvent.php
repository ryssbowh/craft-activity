<?php

namespace Ryssbowh\Activity\events;

use yii\base\Event;

class RegisterFieldLabelsEvent extends Event
{
    /**
     * @var array
     */
    public $labels;
}