<?php

namespace Ryssbowh\Activity\events;

use Ryssbowh\Activity\base\Exporter;
use Ryssbowh\Activity\exceptions\ExporterException;
use Ryssbowh\Activity\exporters\Pdf;
use Ryssbowh\Activity\exporters\Text;
use yii\base\Event;

/**
 * @since 1.3.0
 */
class RegisterExportersEvent extends Event
{
    /**
     * @var array
     */
    protected $_exporters = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->add(new Text);
        $this->add(new Pdf);
    }

    /**
     * Get registered exporters
     * 
     * @return array
     */
    public function getExporters(): array
    {
        return $this->_exporters;
    }

    /**
     * Add a field handler to register
     * 
     * @param Exporter $exporter
     * @param boolean  $replace
     */
    public function add(Exporter $exporter, bool $replace = false)
    {
        if (isset($this->_exporters[$exporter->handle]) and !$replace) {
            throw ExporterException::registered($this->_exporters[$exporter->handle]);
        }
        $this->_exporters[$exporter->handle] = $exporter;
    }
}