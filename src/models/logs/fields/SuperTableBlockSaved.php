<?php

namespace Ryssbowh\Activity\models\logs\fields;

class SuperTableBlockSaved extends SuperTableBlockCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved super table block in field {field}', [
            'field' => $this->fieldName
        ]);
    }
}