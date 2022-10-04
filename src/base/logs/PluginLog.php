<?php

namespace Ryssbowh\Activity\base\logs;

use craft\base\Plugin;

abstract class PluginLog extends SettingsLog
{
    protected $_plugin;

    /**
     * Plugin getter
     *
     * @return ?Plugin
     */
    public function getPlugin(): ?Plugin
    {
        if ($this->_plugin === null and $this->target_class) {
            $this->_plugin = \Craft::$app->plugins->getPlugin($this->target_class);
        }
        return $this->_plugin;
    }

    /**
     * Plugin setter
     * 
     * @param Plugin $plugin
     */
    public function setPlugin(Plugin $plugin)
    {
        $this->_plugin = $plugin;
        $this->target_class = $plugin->handle;
        $this->target_name = $plugin->name;
    }

    /**
     * Get the plugin name
     * 
     * @return string
     */
    public function getPluginName(): string
    {
        if ($this->plugin) {
            return $this->plugin->name;
        }
        return $this->target_name;
    }
}