<?php

namespace Ryssbowh\Activity\services;

use Ryssbowh\Activity\base\Exporter;
use Ryssbowh\Activity\events\RegisterExportersEvent;
use Ryssbowh\Activity\exceptions\ExporterException;
use craft\base\Component;

/**
 * @since 2.3.0
 */
class Exporters extends Component
{
    const EVENT_REGISTER = 'event-register';

    /**
     * @var array
     */
    protected $exporters;

    /**
     * Get all exporters, indexed by handle
     * 
     * @return array
     */
    public function getAll(): array
    {
        if ($this->exporters === null) {
            $this->register();
        }
        return $this->exporters;
    }

    /**
     * Get an exporter by handle
     * 
     * @param  string $handle
     * @return Exporter
     */
    public function getByHandle(string $handle): Exporter
    {
        if (!isset($this->all[$handle])) {
            throw ExporterException::noHandle($handle);
        }
        return $this->exporters[$handle];
    }

    /**
     * Register exporters
     */
    protected function register()
    {
        $event = new RegisterExportersEvent;
        $this->trigger(self::EVENT_REGISTER, $event);
        $this->exporters = $event->exporters;
    }
}