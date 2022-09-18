<?php

namespace Ryssbowh\Activity\models\logs\fields;

class FieldGroupSaved extends FieldGroupCreated
{   
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved field group {name}', ['name' => $this->modelName]);
    }
}