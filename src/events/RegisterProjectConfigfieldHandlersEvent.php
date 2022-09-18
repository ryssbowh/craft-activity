<?php

namespace Ryssbowh\Activity\events;

use Ryssbowh\Activity\exceptions\FieldHandlerException;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\TransportType;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\UserGroup;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\Volume;
use yii\base\Event;

class RegisterProjectConfigfieldHandlersEvent extends Event
{
    protected $_handlers = [];

    public function init()
    {
        parent::init();
        $this->addMany([
            TransportType::class,
            Volume::class,
            UserGroup::class
        ]);
    }

    public function getHandlers(): array
    {
        return $this->_handlers;
    }

    public function add(string $handler, bool $replace = false)
    {
        foreach ($handler::getTargets() as $path) {
            if (isset($this->_handlers[$path]) and !$replace) {
                throw FieldHandlerException::projectConfigRegistered($path, $this->_handlers[$path]);
            }
            $this->_handlers[$path] = $handler;
        }
    }

    public function addMany(array $handlers, bool $replace = false)
    {
        foreach ($handlers as $handler) {
            $this->add($handler, $replace);
        }
    }
}