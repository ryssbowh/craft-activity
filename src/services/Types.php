<?php

namespace Ryssbowh\Activity\services;

use Ryssbowh\Activity\events\RegisterTypesEvent;
use Ryssbowh\Activity\exceptions\ActivityTypeException;
use craft\base\Component;
use craft\db\Query;

class Types extends Component
{   
    const EVENT_REGISTER = 'event-register';

    /**
     * All logs types, indexed by handle
     * @var array
     */
    protected $_types;

    /**
     * Get all registered types
     * 
     * @return array
     */
    public function getTypes(): array
    {
        if ($this->_types === null) {
            $this->register();
        }
        return $this->_types;
    }

    /**
     * Get all types used in database
     * 
     * @return array
     */
    public function getUsedTypes(): array
    {
        $query = (new Query)
            ->select('type')
            ->distinct()
            ->from('{{%activity_logs}}')
            ->all();
        $types = [];
        foreach ($query as $res) {
            try {
                $types[$res['type']] = $this->getTypeClassByHandle($res['type']);
            } catch (ActivityTypeException $e) {}
        }
        return $types;
    }

    /**
     * Get a type class by handle
     * 
     * @param  string $handle
     * @return string
     */
    public function getTypeClassByHandle(string $handle): string
    {
        if ($this->hasType($handle)) {
            return $this->types[$handle];
        }
        throw ActivityTypeException::noHandle($handle);
    }

    /**
     * Is a type handle registered
     * 
     * @param  string  $handle
     * @return boolean
     */
    public function hasType(string $handle): bool
    {
        return isset($this->types[$handle]);
    }

    /**
     * Register types
     */
    protected function register()
    {
        $event = new RegisterTypesEvent;
        $this->trigger(self::EVENT_REGISTER, $event);
        $this->_types = $event->types;
    }
}