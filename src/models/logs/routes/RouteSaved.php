<?php

namespace Ryssbowh\Activity\models\logs\routes;

use Ryssbowh\Activity\base\logs\ActivityLog;

class RouteSaved extends ActivityLog
{   
    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return \Craft::$app->view->renderTemplate('activity/descriptions/route', [
            'log' => $this
        ]);
    }
}