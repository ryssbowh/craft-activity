<?php

namespace Ryssbowh\Activity\models\logs\routes;

use Ryssbowh\Activity\base\logs\SettingsLog;

class RouteCreated extends SettingsLog
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Created route {uid}', [
            'uid' => $this->target_uid
        ]);
    }

    public function getSettingLabels(): array
    {
        return [
            'siteUid' => \Craft::t('app', 'Site'),
            'uriParts' => \Craft::t('app', 'URI'),
            'template' => \Craft::t('app', 'Template'),
        ];
    }
}