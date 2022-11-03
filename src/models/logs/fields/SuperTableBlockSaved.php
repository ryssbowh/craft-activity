<?php

namespace Ryssbowh\Activity\models\logs\fields;

/**
 * @since 2.2.0
 */
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