<?php

namespace Ryssbowh\Activity\models\logs\plugins;

use Ryssbowh\Activity\base\logs\PluginLog;

class PluginEditionChanged extends PluginLog
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Changed edition for plugin {name} from {from} to {to}', [
            'name' => $this->pluginName,
            'from' => $this->data['old'],
            'to' => $this->data['new']
        ]);
    }
}