<?php

namespace Ryssbowh\Activity\models\logs\plugins;

use Ryssbowh\Activity\base\logs\PluginLog;

class PluginEnabled extends PluginLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Enabled plugin {name}', ['name' => $this->pluginName]);
    }
}