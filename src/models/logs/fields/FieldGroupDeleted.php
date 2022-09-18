<?php

namespace Ryssbowh\Activity\models\logs\fields;

class FieldGroupDeleted extends FieldGroupCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted field group {name}', ['name' => $this->target_name]);
    }
}