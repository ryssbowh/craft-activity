<?php

namespace Ryssbowh\Activity\models\logs\entries;

class EntryTypeDeleted extends EntryTypeCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted entry type {name}', [
            'name' => $this->target_name
        ]);
    }
}
