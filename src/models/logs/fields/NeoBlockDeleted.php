<?php

namespace Ryssbowh\Activity\models\logs\fields;

/**
 * @since 1.3.1
 */
class NeoBlockDeleted extends NeoBlockCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted neo block {name} in field {field}', [
            'name' => $this->modelName,
            'field' => $this->fieldName
        ]);
    }
}