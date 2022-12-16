<?php

namespace Ryssbowh\Activity\models\logs\fields;

/**
 * @since 2.3.1
 */
class NeoBlockSaved extends NeoBlockCreated
{
    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return \Craft::t('activity', 'Saved neo block {name} in field {field}', [
            'name' => $this->modelName,
            'field' => $this->fieldName
        ]);
    }
}