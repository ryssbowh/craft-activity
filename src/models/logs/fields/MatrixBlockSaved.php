<?php

namespace Ryssbowh\Activity\models\logs\fields;

class MatrixBlockSaved extends MatrixBlockCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved matrix block {name} in field {field}', [
            'name' => $this->modelName,
            'field' => $this->fieldName
        ]);
    }
}