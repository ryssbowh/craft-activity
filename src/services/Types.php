<?php

namespace Ryssbowh\Activity\services;

use Ryssbowh\Activity\events\RegisterTypesEvent;
use Ryssbowh\Activity\exceptions\ActivityTypeException;
use craft\base\Component;
use craft\db\Query;

class Types extends Component
{   
    const EVENT_REGISTER = 'event-register';

    protected $_types;

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
            $types[$res['type']] = $this->getTypeClassByHandle($res['type']);
        }
        return $types;
    }

    public function getTypeClassByHandle(string $handle): string
    {
        if ($this->hasType($handle)) {
            return $this->types[$handle];
        }
        throw ActivityTypeException::noHandle($handle);
    }

    public function hasType(string $handle): bool
    {
        return isset($this->types[$handle]);
    }

    protected function register()
    {
        $event = new RegisterTypesEvent;
        $this->trigger(self::EVENT_REGISTER, $event);
        $this->_types = $event->types;
    }
}