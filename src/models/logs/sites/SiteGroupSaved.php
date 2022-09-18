<?php

namespace Ryssbowh\Activity\models\logs\sites;

class SiteGroupSaved extends SiteGroupCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved site group {name}', ['name' => $this->modelName]);
    }
}