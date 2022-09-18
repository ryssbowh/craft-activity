<?php

namespace Ryssbowh\Activity\base;

abstract class SettingsLog extends ActivityLog
{
    /**
     * Get the labels for the settings
     * 
     * @return array
     */
    public function getSettingLabels(): array
    {
        return [];
    }

    /**
     * Get the labels for one setting
     * 
     * @return ?string
     */
    public function getSettingLabel(string $name): ?string
    {
        return $this->getSettingLabels()[$name] ?? null;
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