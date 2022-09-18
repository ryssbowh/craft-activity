<?php

namespace Ryssbowh\Activity\models\logs\plugins;

use Ryssbowh\Activity\base\PluginLog;

class PluginDisabled extends PluginLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Disabled plugin {name}', ['name' => $this->pluginName]);
    }
}