<?php

namespace Ryssbowh\Activity\models\logs\fields;

/**
 * @since 2.3.1
 */
class NeoBlockGroupDeleted extends NeoBlockGroupCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted neo block group {name} in field {field}', [
            'name' => $this->modelName,
            'field' => $this->fieldName
        ]);
    }
}