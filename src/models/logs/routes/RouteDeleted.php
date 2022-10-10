<?php

namespace Ryssbowh\Activity\models\logs\routes;

class RouteDeleted extends RouteCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted route {uid}', [
            'uid' => $this->target_uid
        ]);
    }
}