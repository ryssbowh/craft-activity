<?php

namespace Ryssbowh\Activity\base;

use craft\base\Component;

/**
 * @since 2.3.0
 */
abstract class Exporter extends Component
{   
    /**
     * Get this exporter's handle
     * 
     * @return string
     */
    abstract public function getHandle(): string;

    /**
     * Get this exporter label, will be shown on the backend dropdown
     * 
     * @return string
     */
    abstract public function getLabel(): string;

    /**
     * Export some logs
     *
     * @param  array $logs
     * @return string
     */
    abstract public function getExportContent(array $logs): string;

    /**
     * Get the extension of the file this exporter will generate (without dot)
     * 
     * @return string
     */
    abstract public function getExtension(): string;

    /**
     * Get the mime type of the file this exporter will generate
     * 
     * @return string
     */
    abstract public function getMimeType(): string;
}