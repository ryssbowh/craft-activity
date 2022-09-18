<?php

namespace Ryssbowh\Activity\models\logs\sites;

class SiteGroupDeleted extends SiteGroupCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted site group {name}', ['name' => $this->target_name]);
    }
}