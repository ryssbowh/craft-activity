<?php

namespace Ryssbowh\Activity\models\logs\fields;

/**
 * @since 1.2.0
 */
class MatrixBlockDeleted extends MatrixBlockCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted matrix block {name} in field {field}', [
            'name' => $this->modelName,
            'field' => $this->fieldName
        ]);
    }
}