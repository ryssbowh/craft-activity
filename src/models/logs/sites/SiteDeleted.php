<?php

namespace Ryssbowh\Activity\models\logs\sites;

class SiteDeleted extends SiteCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted site {name}', ['name' => $this->target_name]);
    }
}