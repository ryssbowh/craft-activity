<?php

namespace Ryssbowh\Activity\twig;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\services\Logs;

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