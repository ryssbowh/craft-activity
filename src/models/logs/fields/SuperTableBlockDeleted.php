<?php

namespace Ryssbowh\Activity\models\logs\fields;

/**
 * @since 2.2.0
 */
class SuperTableBlockDeleted extends SuperTableBlockCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Deleted super table block in field {field}', [
            'field' => $this->fieldName
        ]);
    }
}