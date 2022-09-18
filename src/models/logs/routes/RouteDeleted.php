<?php

namespace Ryssbowh\Activity\models\logs\routes;

use Ryssbowh\Activity\base\ActivityLog;

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