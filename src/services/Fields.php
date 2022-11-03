<?php

namespace Ryssbowh\Activity\services;

use Ryssbowh\Activity\events\RegisterFieldLabelsEvent;
use Ryssbowh\Activity\events\RegisterFieldTypingsEvent;
use Ryssbowh\Activity\events\RegisterTrackedFieldsEvent;
use Ryssbowh\Activity\events\RegisterTypesEvent;
use Ryssbowh\Activity\exceptions\ActivityTypeException;
use craft\base\Component;
use yii\base\Event;

/**
 * @since 2.2.0
 */
class Fields extends Component
{   
    const EVENT_REGISTER_TRACKED_FIELDS = 'register-tracked-fields';
    const EVENT_REGISTER_FIELD_TYPINGS = 'register-field-typings';
    const EVENT_REGISTER_FIELD_LABELS = 'register-field-labels';

    /**
     * @var array
     */
    protected $trackedFields;

    /**
     * @var array
     */
    protected $fieldTypings;

    /**
     * @var array
     */
    protected $fieldLabels;

    /**
     * Get tracked config names for a field type
     *
     * @param  string $type
     * @return array
     */
    public function getTrackedFieldNames(string $type): array
    {
        if ($this->trackedFields === null) {
            $event = new RegisterTrackedFieldsEvent();
            Event::trigger($this, self::EVENT_REGISTER_TRACKED_FIELDS, $event);
            $this->trackedFields = $event->tracked;
        }
        return array_merge($this->trackedFields['_base'], $this->trackedFields[$type] ?? []);
    }

    /**
     * Get fields typings for a field type
     *
     * @param  string $type
     * @return array
     */
    public function getTrackedFieldTypings(string $type): array
    {
        if ($this->fieldTypings === null) {
            $event = new RegisterFieldTypingsEvent();
            Event::trigger($this, self::EVENT_REGISTER_FIELD_TYPINGS, $event);
            $this->fieldTypings = $event->typings;
        }
        return array_merge($this->fieldTypings['_base'], $this->fieldTypings[$type] ?? []);
    }

    /**
     * Get the labels for for a field type
     *
     * @param  string $type
     * @return array
     */
    public function getFieldLabels(string $type): array
    {
        if ($this->fieldLabels === null) {
            $event = new RegisterFieldLabelsEvent();
            Event::trigger($this, self::EVENT_REGISTER_FIELD_LABELS, $event);
            $this->fieldLabels = $event->labels;
        }
        return array_merge($this->fieldLabels['_base'], $this->fieldLabels[$type] ?? []);
    }
}