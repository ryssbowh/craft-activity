<?php

namespace Ryssbowh\Activity\models\logs\fields;

/**
 * @since 2.3.1
 */
class NeoBlockGroupSaved extends NeoBlockGroupCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved neo block group {name} in field {field}', [
            'name' => $this->modelName,
            'field' => $this->fieldName
        ]);
    }
}