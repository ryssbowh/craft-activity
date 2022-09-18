<?php

namespace Ryssbowh\Activity\models\logs\sites;

class SiteSaved extends SiteCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved site {name}', ['name' => $this->modelName]);
    }
}