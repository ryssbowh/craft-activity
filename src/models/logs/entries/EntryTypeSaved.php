<?php

namespace Ryssbowh\Activity\models\logs\entries;

class EntryTypeSaved extends EntryTypeCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved entry type {name}', [
            'name' => $this->modelName
        ]);
    }
}
