<?php

namespace Ryssbowh\Activity\models\logs\fields;

class FieldSaved extends FieldCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved field {name}', ['name' => $this->modelName]);
    }
}