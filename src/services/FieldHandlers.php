<?php

namespace Ryssbowh\Activity\services;

use Ryssbowh\Activity\base\FieldHandler;
use Ryssbowh\Activity\events\RegisterConfigFieldHandlersEvent;
use Ryssbowh\Activity\events\RegisterElementFieldHandlersEvent;
use Ryssbowh\Activity\events\RegisterProjectConfigfieldHandlersEvent;
use Ryssbowh\Activity\models\fieldHandlers\config\DefaultHandler;
use Ryssbowh\Activity\models\fieldHandlers\elements\Unknown;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\DefaultHandler as ProjectConfigDefaultHandler;
use craft\base\Component;

class FieldHandlers extends Component
{
    const EVENT_REGISTER_CONFIG_HANDLERS = 'event-register-config-handlers';
    const EVENT_REGISTER_ELEMENT_HANDLERS = 'event-register-element-handlers';
    const EVENT_REGISTER_PROJECTCONFIG_HANDLERS = 'event-register-projectconfig-handlers';

    protected $_configHandlers;
    protected $_elementHandlers;
    protected $_projectConfigHandlers;

    public function getConfigHandlers(): array
    {
        if ($this->_configHandlers === null) {
            $this->registerConfigHandlers();
        }
        return $this->_configHandlers;
    }

    public function getElementHandlers(): array
    {
        if ($this->_elementHandlers === null) {
            $this->registerElementHandlers();
        }
        return $this->_elementHandlers;
    }

    public function getProjectConfigHandlers(): array
    {
        if ($this->_projectConfigHandlers === null) {
            $this->registerProjectConfigHandlers();
        }
        return $this->_projectConfigHandlers;
    }

    public function hasConfigHandler(string $modelClass, string $name): bool
    {
        return isset($this->configHandlers[$modelClass][$name]);
    }

    public function hasElementHandler(string $fieldClass): bool
    {
        return isset($this->elementHandlers[$fieldClass]);
    }

    public function hasProjectConfigHandler(string $path): bool
    {
        return isset($this->projectConfigHandlers[$path]);
    }

    public function getForElementField(string $fieldClass): string
    {
        if ($this->hasElementHandler($fieldClass)) {
            return $this->elementHandlers[$fieldClass];
        }
        return Unknown::class;
    }

    public function getForConfigField(string $modelClass, string $name): string
    {
        if ($this->hasConfigHandler($modelClass, $name)) {
            return $this->configHandlers[$modelClass][$name];
        }
        return DefaultHandler::class;
    }

    public function getForProjectConfigPath(string $path): string
    {
        if ($this->hasProjectConfigHandler($path)) {
            return $this->projectConfigHandlers[$path];
        }
        return ProjectConfigDefaultHandler::class;
    }

    protected function registerConfigHandlers()
    {
        $event = new RegisterConfigFieldHandlersEvent;
        $this->trigger(self::EVENT_REGISTER_CONFIG_HANDLERS, $event);
        $this->_configHandlers = $event->handlers;
    }

    protected function registerElementHandlers()
    {
        $event = new RegisterElementFieldHandlersEvent;
        $this->trigger(self::EVENT_REGISTER_ELEMENT_HANDLERS, $event);
        $this->_elementHandlers = $event->handlers;
    }

    protected function registerProjectConfigHandlers()
    {
        $event = new RegisterProjectConfigfieldHandlersEvent;
        $this->trigger(self::EVENT_REGISTER_PROJECTCONFIG_HANDLERS, $event);
        $this->_projectConfigHandlers = $event->handlers;
    }
}