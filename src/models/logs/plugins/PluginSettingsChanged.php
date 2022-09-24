<?php

namespace Ryssbowh\Activity\models\logs\plugins;

use Ryssbowh\Activity\base\logs\PluginLog;

class PluginSettingsChanged extends PluginLog
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Changed settings for plugin {name}', ['name' => $this->pluginName]);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return \Craft::$app->view->renderTemplate('activity/descriptions/settings', [
            'log' => $this
        ]);
    }
}