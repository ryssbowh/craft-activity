<?php

namespace Ryssbowh\Activity\twig;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\helpers\PrettyPrint;
use Ryssbowh\Activity\services\Logs;
use craft\helpers\Json;

/**
 * @since 1.2.0
 */
class TwigActivity
{
    /**
     * Get the current twig context, can be 'web' or 'export'
     *
     * @since  1.3.5
     * @return string
     */
    public function getContext(): string
    {
        return Activity::$plugin->twigContext;
    }

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
     * @since 1.3.4
     * @return bool
     */
    public function showUserIP(): bool
    {
        return Activity::$plugin->settings->showUserIP;
    }

    /**
     * Return an element value
     *
     * @param   $value
     * @return string
     * @since 1.3.9
     */
    public function elementValue($value): string
    {
        if (is_array($value)) {
            return Json::encode($value);
        } else {
            $value = PrettyPrint::get($value);
        }
        return $value;
    }
}
