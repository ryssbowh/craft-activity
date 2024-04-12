<?php

namespace Ryssbowh\Activity\models\logs\fields;

/**
 * @deprecated in 3.0.0
 */
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
