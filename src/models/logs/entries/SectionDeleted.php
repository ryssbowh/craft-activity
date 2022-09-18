<?php

namespace Ryssbowh\Activity\models\logs\entries;

class SectionDeleted extends SectionCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted section {name}', ['name' => $this->target_name]);
    }
}