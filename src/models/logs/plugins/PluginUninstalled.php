<?php

namespace Ryssbowh\Activity\models\logs\plugins;

use Ryssbowh\Activity\base\logs\PluginLog;

class PluginUninstalled extends PluginLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Uninstalled plugin {name}', ['name' => $this->pluginName]);
    }
}