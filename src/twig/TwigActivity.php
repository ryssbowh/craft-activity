<?php

namespace Ryssbowh\Activity\twig;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\services\Logs;

/**
 * @since 1.2.0
 */
class TwigActivity
{
    /**
     * Get logs service
     * 
     * @return Logs
     */
    public function getLogs(): Logs
    {
        return Activity::$plugin->logs;
    }
}