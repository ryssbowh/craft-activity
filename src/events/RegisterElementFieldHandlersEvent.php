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
    /**
     * @var array
     */
    protected $_handlers = [];

    /**
     * @inheritDoc
     */
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

    /**
     * Get registered field handlers
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->_handlers;
    }

    /**
     * Add a field handler to register
     * 
     * @param string  $handler
     * @param boolean $replace
     */
    public function add(string $handler, bool $replace = false)
    {
        foreach ($handler::getTargets() as $target) {
            if (isset($this->_handlers[$target]) and !$replace) {
                throw FieldHandlerException::elementRegistered($target, $this->_handlers[$target]);
            }
            $this->_handlers[$target] = $handler;
        }
    }

    /**
     * Add many field handlers to register
     * 
     * @param array   $handlers
     * @param boolean $replace
     */
    public function addMany(array $handlers, bool $replace = false)
    {
        foreach ($handlers as $handler) {
            $this->add($handler, $replace);
        }
    }
}