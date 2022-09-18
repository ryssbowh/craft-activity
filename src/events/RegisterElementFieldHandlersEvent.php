<?php

namespace Ryssbowh\Activity\events;

use Ryssbowh\Activity\exceptions\FieldHandlerException;
use Ryssbowh\Activity\models\fieldHandlers\elements\Date;
use Ryssbowh\Activity\models\fieldHandlers\elements\Elements;
use Ryssbowh\Activity\models\fieldHandlers\elements\ListField;
use Ryssbowh\Activity\models\fieldHandlers\elements\ListsField;
use Ryssbowh\Activity\models\fieldHandlers\elements\Plain;
use Ryssbowh\Activity\models\fieldHandlers\elements\Redactor;
use yii\base\Event;

class RegisterElementFieldHandlersEvent extends Event
{
    protected $_handlers = [];

    public function init()
    {
        parent::init();
        $this->addMany([
            Elements::class,
            ListField::class,
            ListsField::class,
            Plain::class,
            Redactor::class,
            Date::class
        ]);
    }

    public function getHandlers(): array
    {
        return $this->_handlers;
    }

    public function add(string $handler, bool $replace = false)
    {
        foreach ($handler::getTargets() as $target) {
            if (isset($this->_handlers[$target]) and !$replace) {
                throw FieldHandlerException::elementRegistered($target, $this->_handlers[$target]);
            }
            $this->_handlers[$target] = $handler;
        }
    }

    public function addMany(array $handlers, bool $replace = false)
    {
        foreach ($handlers as $handler) {
            $this->add($handler, $replace);
        }
    }
}