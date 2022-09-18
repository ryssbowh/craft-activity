<?php

namespace Ryssbowh\Activity\models\logs\fields;

class FieldDeleted extends FieldCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted field {name}', ['name' => $this->target_name]);
    }
}