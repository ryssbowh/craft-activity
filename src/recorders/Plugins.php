<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\Recorder;
use Ryssbowh\Activity\traits\ProjectConfigFields;
use craft\base\Plugin;
use craft\services\Plugins as CraftPlugins;
use yii\base\Event;

class Plugins extends Recorder
{
    use ProjectConfigFields;

    /**
     * Old plugin settings
     * @var array
     */
    protected $settings = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        Event::on(CraftPlugins::class, CraftPlugins::EVENT_AFTER_INSTALL_PLUGIN, function ($event) {
            Activity::getRecorder('plugins')->onInstalled($event->plugin);
        });
        Event::on(CraftPlugins::class, CraftPlugins::EVENT_AFTER_UNINSTALL_PLUGIN, function ($event) {
            Activity::getRecorder('plugins')->onUninstalled($event->plugin);
        });
        Event::on(CraftPlugins::class, CraftPlugins::EVENT_AFTER_ENABLE_PLUGIN, function ($event) {
            Activity::getRecorder('plugins')->onEnabled($event->plugin);
        });
        Event::on(CraftPlugins::class, CraftPlugins::EVENT_AFTER_DISABLE_PLUGIN, function ($event) {
            Activity::getRecorder('plugins')->onDisabled($event->plugin);
        });
        Event::on(CraftPlugins::class, CraftPlugins::EVENT_BEFORE_SAVE_PLUGIN_SETTINGS, function ($event) {
            Activity::getRecorder('plugins')->beforeSettingsSaved($event->plugin);
        });
        Event::on(CraftPlugins::class, CraftPlugins::EVENT_AFTER_SAVE_PLUGIN_SETTINGS, function ($event) {
            Activity::getRecorder('plugins')->onSettingsSaved($event->plugin);
        });
    }

    /**
     * Save a log when a plugin is installed
     * 
     * @param Plugin $plugin
     */
    public function onInstalled(Plugin $plugin)
    {
        if (!$this->shouldSaveLog('pluginInstalled')) {
            return;
        }
        $this->saveLog('pluginInstalled', [
            'plugin' => $plugin
        ]);
    }

    /**
     * Save a log when a plugin is uninstalled
     * 
     * @param Plugin $plugin
     */
    public function onUninstalled(Plugin $plugin)
    {
        if ($plugin->handle == 'activity' or !$this->shouldSaveLog('pluginUninstalled')) {
            return;
        }
        $this->saveLog('pluginUninstalled', [
            'plugin' => $plugin
        ]);
    }

    /**
     * Save a log when a plugin is enabled
     * 
     * @param Plugin $plugin
     */
    public function onEnabled(Plugin $plugin)
    {
        if (!$this->shouldSaveLog('pluginEnabled')) {
            return;
        }
        $this->saveLog('pluginEnabled', [
            'plugin' => $plugin
        ]);
    }

    /**
     * Save a log when a plugin is disabled
     * 
     * @param Plugin $plugin
     */
    public function onDisabled(Plugin $plugin)
    {
        if (!$this->shouldSaveLog('pluginDisabled')) {
            return;
        }
        $this->saveLog('pluginDisabled', [
            'plugin' => $plugin
        ]);
    }

    /**
     * Save a log when a plugin settings are saved
     * 
     * @param Plugin $plugin
     */
    public function onSettingsSaved(Plugin $plugin)
    {
        if (!$this->shouldSaveLog('pluginSettingsChanged')) {
            return;
        }
        $settings = \Craft::$app->projectConfig->get(CraftPlugins::CONFIG_PLUGINS_KEY . '.' . $plugin->handle . '.settings');
        $this->saveLog('pluginSettingsChanged', [
            'plugin' => $plugin,
            'changedFields' => $this->getDirtyConfig(CraftPlugins::CONFIG_PLUGINS_KEY . '.' . $plugin->handle . '.settings', $settings, $this->settings[$plugin->handle] ?? [])
        ]);
    }

    /**
     * Save a old plugin settings before they're saved
     * 
     * @param Plugin $plugin
     */
    public function beforeSettingsSaved(Plugin $plugin)
    {
        if (!$this->shouldSaveLog('pluginSettingsChanged')) {
            return;
        }
        $this->settings[$plugin->handle] = \Craft::$app->projectConfig->get(CraftPlugins::CONFIG_PLUGINS_KEY . '.' . $plugin->handle . '.settings');
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedConfigNames()
    {
        return '*';
    }
}