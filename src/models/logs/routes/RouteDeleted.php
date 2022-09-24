<?php

namespace Ryssbowh\Activity\models\logs\routes;

use Ryssbowh\Activity\base\logs\ActivityLog;

class RouteDeleted extends ActivityLog
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted route');
    }
}