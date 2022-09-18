<?php

namespace Ryssbowh\Activity\models\logs\plugins;

use Ryssbowh\Activity\base\PluginLog;

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