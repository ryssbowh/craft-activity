<?php

namespace Ryssbowh\Activity\events;

use craft\fields\Date;
use craft\fields\PlainText;
use yii\base\Event;

/**
 * @since 1.2.0
 */
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
        $this->typings[PlainText::class] = [
            'settings.code' => 'bool',
            'settings.multiline' => 'bool'
        ];
        $this->typings[Date::class] = [
            'settings.showTimeZone' => 'bool'
        ];
    }
}