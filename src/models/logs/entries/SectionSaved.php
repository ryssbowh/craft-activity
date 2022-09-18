<?php

namespace Ryssbowh\Activity\models\logs\entries;

class SectionSaved extends SectionCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved section {name}', ['name' => $this->modelName]);
    }
}