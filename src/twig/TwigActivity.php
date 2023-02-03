<?php

namespace Ryssbowh\Activity\twig;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\services\Logs;

/**
 * @since 2.2.0
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

    /**
     * Show user IPs
     *
     * @since 2.3.4
     * @return bool
     */
    public function showUserIP(): bool
    {
        return Activity::$plugin->settings->showUserIP;
    }
}
