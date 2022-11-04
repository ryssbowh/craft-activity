<?php

namespace Ryssbowh\Activity\recorders;

use Ryssbowh\Activity\Activity;
use Ryssbowh\Activity\base\recorders\ConfigModelRecorder;
use craft\services\Routes as CraftRoutes;
use yii\base\Event;

class Routes extends ConfigModelRecorder
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        \Craft::$app->projectConfig->onAdd(CraftRoutes::CONFIG_ROUTES_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('routes')->onAdd($event);
        });
        \Craft::$app->projectConfig->onUpdate(CraftRoutes::CONFIG_ROUTES_KEY . '.{uid}', function (Event $event) {
            Activity::getRecorder('routes')->onUpdate($event);
        });
        \Craft::$app->projectConfig->onRemove(CraftRoutes::CONFIG_ROUTES_KEY . '.{uid}', function (Event $event) {
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
    protected function _getTrackedFieldNames(): array
    {
        return ['siteUid', 'uriParts', 'template'];
    }
}