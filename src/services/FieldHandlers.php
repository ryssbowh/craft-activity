<?php

namespace Ryssbowh\Activity\services;

use Ryssbowh\Activity\base\fieldHandlers\FieldHandler;
use Ryssbowh\Activity\events\RegisterElementFieldHandlersEvent;
use Ryssbowh\Activity\events\RegisterHandlerTargetsEvent;
use Ryssbowh\Activity\events\RegisterProjectConfigfieldHandlersEvent;
use Ryssbowh\Activity\models\fieldHandlers\elements\Unknown;
use Ryssbowh\Activity\models\fieldHandlers\projectConfig\DefaultHandler;
use craft\base\Component;
use craft\base\Element;
use craft\base\Field;
use yii\base\Event;

class FieldHandlers extends Component
{
    public const EVENT_REGISTER_ELEMENT_HANDLERS = 'event-register-element-handlers';
    public const EVENT_REGISTER_PROJECTCONFIG_HANDLERS = 'event-register-projectconfig-handlers';

    /**
     * Element fields handlers
     * @var array
     */
    protected $_elementHandlers;

    /**
     * Project config fields handlers
     * @var array
     */
    protected $_projectConfigHandlers;

    /**
     * @var array
     */
    protected $handlerTargets = [];

    /**
     * Get element handlers, indexed by field class
     *
     * @return array
     */
    public function getElementHandlers(): array
    {
        if ($this->_elementHandlers === null) {
            $this->registerElementHandlers();
        }
        return $this->_elementHandlers;
    }

    /**
     * Get project config field handlers, indexed by config path
     *
     * @return array
     */
    public function getProjectConfigHandlers(): array
    {
        if ($this->_projectConfigHandlers === null) {
            $this->registerProjectConfigHandlers();
        }
        return $this->_projectConfigHandlers;
    }

    /**
     * Does a field have a field handler registered
     *
     * @param  string  $fieldClass
     * @return boolean
     */
    public function hasElementHandler(string $fieldClass): bool
    {
        return isset($this->elementHandlers[$fieldClass]);
    }

    /**
     * Does a path have a field handler registered
     *
     * @param  string  $path
     * @return boolean
     */
    public function hasProjectConfigHandler(string $path): bool
    {
        return isset($this->projectConfigHandlers[$path]);
    }

    /**
     * Get the field handler class for an element field class
     *
     * @param  string $fieldClass
     * @return string
     */
    public function getForElementField(string $fieldClass): string
    {
        if ($this->hasElementHandler($fieldClass)) {
            return $this->elementHandlers[$fieldClass];
        }
        return Unknown::class;
    }

    /**
     * Get a field handler instance for a field and an element
     *
     * @param Field   $field
     * @param Element $element
     * @param ?string $label
     * @return FieldHandler
     * @since 2.4.0
     */
    public function getHandlerForField(Field $field, Element $element, ?string $label = null): FieldHandler
    {
        $class = $this->getForElementField(get_class($field));
        $fieldValue = $element->getFieldValue($field->handle);
        return new $class([
            'field' => $field,
            'element' => $element,
            'name' => $label ?: $field->name,
            'value' => $field->serializeValue($fieldValue, $element),
            'rawValue' => $fieldValue
        ]);
    }

    /**
     * Get the field handler for a project config path
     *
     * @param  string $path
     * @return string
     */
    public function getForProjectConfigPath(string $path): string
    {
        if ($this->hasProjectConfigHandler($path)) {
            return $this->projectConfigHandlers[$path];
        }
        return DefaultHandler::class;
    }

    /**
     * Registers element field handlers
     */
    protected function registerElementHandlers()
    {
        $event = new RegisterElementFieldHandlersEvent();
        $this->trigger(self::EVENT_REGISTER_ELEMENT_HANDLERS, $event);
        $this->_elementHandlers = $event->handlers;
    }

    /**
     * Get the targets for a handler
     *
     * @param  string $class
     * @param  array  $default
     * @return array
     */
    public function getHandlerTargets(string $class, array $default): array
    {
        if (!isset($this->handlerTargets[$class])) {
            $event = new RegisterHandlerTargetsEvent([
                'targets' => $default
            ]);
            Event::trigger($class, $class::EVENT_REGISTER_TARGETS, $event);
            $this->handlerTargets[$class] = $event->targets;
        }
        return $this->handlerTargets[$class];
    }

    /**
     * Register project config field handlers
     */
    protected function registerProjectConfigHandlers()
    {
        $event = new RegisterProjectConfigfieldHandlersEvent();
        $this->trigger(self::EVENT_REGISTER_PROJECTCONFIG_HANDLERS, $event);
        $this->_projectConfigHandlers = $event->handlers;
    }
}
