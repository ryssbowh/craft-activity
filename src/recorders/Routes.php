<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\ProjectConfig;
use yii\base\Event;

class Routes extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        \Craft::$app->projectConfig->onAdd(ProjectConfig::PATH_ROUTES . '.{uid}', function (Event $event) {
            Activity::getRecorder('routes')->onAdd($event);
        });
        \Craft::$app->projectConfig->onUpdate(ProjectConfig::PATH_ROUTES . '.{uid}', function (Event $event) {
            Activity::getRecorder('routes')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onRemove(ProjectConfig::PATH_ROUTES . '.{uid}', function (Event $event) {
            Activity::getRecorder('routes')->onRemove($event);
        });
    }

    /**
     * @inheritDoc
     */
    protected function getActivityHandle(): string
    {
        return 'route';
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFieldNames(): array
    {
        return ['siteUid', 'uriParts', 'template'];
    }
}