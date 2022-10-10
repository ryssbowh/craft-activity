<?php

namespace Ryssbowh\Activity\models\logs\routes;

class RouteSaved extends RouteCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved route {uid}', [
            'uid' => $this->target_uid
        ]);
    }
}