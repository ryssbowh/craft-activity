<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\Recorder;
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
        \Craft::$app->projectConfig->onUpdate(CraftPlugins::CONFIG_PLUGINS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('plugins')->onChanged($event);
        });
        \Craft::$app->projectConfig->onAdd(CraftPlugins::CONFIG_PLUGINS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('plugins')->onInstalled($event);
        });
        \Craft::$app->projectConfig->onRemove(CraftPlugins::CONFIG_PLUGINS_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('plugins')->onUninstalled($event);
        });
        // Event::on(CraftPlugins::class, CraftPlugins::EVENT_AFTER_INSTALL_PLUGIN, function ($event) {
        //     Activity::getRecorder('plugins')->onInstalled($event->plugin);
        // });
        // Event::on(CraftPlugins::class, CraftPlugins::EVENT_AFTER_UNINSTALL_PLUGIN, function ($event) {
        //     Activity::getRecorder('plugins')->onUninstalled($event->plugin);
        // });
        // Event::on(CraftPlugins::class, CraftPlugins::EVENT_AFTER_ENABLE_PLUGIN, function ($event) {
        //     Activity::getRecorder('plugins')->onEnabled($event->plugin);
        // });
        // Event::on(CraftPlugins::class, CraftPlugins::EVENT_AFTER_DISABLE_PLUGIN, function ($event) {
        //     Activity::getRecorder('plugins')->onDisabled($event->plugin);
        // });
        // Event::on(CraftPlugins::class, CraftPlugins::EVENT_BEFORE_SAVE_PLUGIN_SETTINGS, function ($event) {
        //     Activity::getRecorder('plugins')->beforeSettingsSaved($event->plugin);
        // });
        // Event::on(CraftPlugins::class, CraftPlugins::EVENT_AFTER_SAVE_PLUGIN_SETTINGS, function ($event) {
        //     Activity::getRecorder('plugins')->onSettingsSaved($event->plugin);
        // });
    }

    public function onChanged(Event $event)
    {
        $handle = $event->tokenMatches[0];
        $dirtySettings = $this->getDirtyConfig(CraftPlugins::CONFIG_PLUGINS_KEY . '.' . $handle . '.settings', $event->newValue['settings'] ?? [], $event->oldValue['settings'] ?? []);
        if ($dirtySettings) {
            $this->onSettingsChanged($handle, $dirtySettings);
        }
        if ($event->oldValue['enabled'] and !$event->newValue['enabled']) {
            $this->onDisabled($handle);
        }
        if (!$event->oldValue['enabled'] and $event->newValue['enabled']) {
            $this->onEnabled($handle);
        }
        if ($event->oldValue['edition'] != $event->newValue['edition']) {
            $this->onEditionChanged($handle, $event->newValue['edition'], $event->oldValue['edition']);
        }
    }

    /**
     * Save a log when a plugin is installed
     * 
     * @param Event $event
     */
    public function onInstalled(Event $event)
    {
        $handle = $event->tokenMatches[0];
        if (!$this->shouldSaveLog('pluginInstalled')) {
            return;
        }
        $info = \Craft::$app->plugins->getPluginInfo($handle);
        $this->commitLog('pluginInstalled', [
            'target_class' => $handle,
            'target_name' => $info['name']
        ]);
    }

    /**
     * Save a log when a plugin is uninstalled
     * 
     * @param Event $event
     */
    public function onUninstalled(Event $event)
    {
        $handle = $event->tokenMatches[0];
        if ($handle == 'activity' or !$this->shouldSaveLog('pluginUninstalled')) {
            return;
        }
        $info = \Craft::$app->plugins->getPluginInfo($handle);
        $this->commitLog('pluginUninstalled', [
            'target_class' => $handle,
            'target_name' => $info['name']
        ]);
    }

    /**
     * Save a log when a plugin is enabled
     *
     * @param string $handle
     */
    public function onEnabled(string $handle)
    {
        if (!$this->shouldSaveLog('pluginEnabled')) {
            return;
        }
        $info = \Craft::$app->plugins->getPluginInfo($handle);
        $this->commitLog('pluginEnabled', [
            'target_class' => $handle,
            'target_name' => $info['name']
        ]);
    }

    /**
     * Save a log when a plugin is disabled
     * 
     * @param string $handle
     */
    public function onDisabled(string $handle)
    {
        if (!$this->shouldSaveLog('pluginDisabled')) {
            return;
        }
        $info = \Craft::$app->plugins->getPluginInfo($handle);
        $this->commitLog('pluginDisabled', [
            'target_class' => $handle,
            'target_name' => $info['name']
        ]);
    }

    /**
     * Save a log when a plugin edition is changed
     * 
     * @param string $handle
     * @param string $oldEdition
     * @param string $newEdition
     */
    public function onEditionChanged(string $handle, string $newEdition, string $oldEdition)
    {
        if (!$this->shouldSaveLog('pluginEditionChanged')) {
            return;
        }
        $info = \Craft::$app->plugins->getPluginInfo($handle);
        $this->commitLog('pluginEditionChanged', [
            'target_class' => $handle,
            'target_name' => $info['name'],
            'data' => [
                'old' => $oldEdition,
                'new' => $newEdition
            ]
        ]);
    }

    /**
     * Save a log when a plugin settings are saved
     * 
     * @param string $handle
     * @param array  $dirty
     */
    public function onSettingsChanged(string $handle, array $dirty)
    {
        if (!$this->shouldSaveLog('pluginSettingsChanged')) {
            return;
        }
        $info = \Craft::$app->plugins->getPluginInfo($handle);
        $this->commitLog('pluginSettingsChanged', [
            'target_class' => $handle,
            'target_name' => $info['name'],
            'changedFields' => $dirty
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames()
    {
        return '*';
    }

    /**
     * Populate the latest logs created properly with the plugin instance. 
     * When the plugin was installed/enabled, the plugin instance couldn't be retrieved
     * 
     * @param string $handle
     */
    protected function populateLatestLogs(string $handle)
    {
        Event::on(CraftPlugins::class, CraftPlugins::EVENT_AFTER_INSTALL_PLUGIN, function ($event) use ($handle) {
            $plugin = \Craft::$app->plugins->getPlugin($handle);
            foreach (Activity::getRecorder('plugins')->queue as $log) {
                if ($log->target_class == $handle) {
                    $log->plugin = $plugin;
                }
            }
        });
    }
}